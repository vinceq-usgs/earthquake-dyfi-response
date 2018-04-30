#! /bin/bash

## Health check script for docker container
# Check for recent responses (within RESPONSE_MAX_AGE_MINUTES minutes).
# If no recent responses are found, send one and check again.


BACKUP_DIR=${BACKUP_DIR:-'/backup'}
RESPONSES_DIR="${BACKUP_DIR}/responses"
RESPONSE_MAX_AGE_MINUTES=1


if [ ! -d $RESPONSES_DIR ]; then
  mkdir -p $RESPONSES_DIR
  chmod 777 $RESPONSES_DIR
fi


# check for responses within the past minute
num_responses=$(find $RESPONSES_DIR -mmin -${RESPONSE_MAX_AGE_MINUTES} -name '*' -type f | wc -l)
echo "Found ${num_responses} responses in past ${RESPONSE_MAX_AGE_MINUTES} minutes"
if [ "${num_responses}" != "0" ]; then
  exit 0
fi


# no response found, send one
echo "Submitting response"
status=$(
    curl \
        --request POST \
        --data "eventid=healthcheck&fldSituation_felt=0&format=json" \
        --max-time 5 \
        --output /dev/null \
        --silent \
        --user-agent "Docker Healthcheck" \
        --write-out "%{http_code}" \
        http://localhost/response.php
)
if [ "${status}" != "200" ]; then
  echo "Error submitting response, expected 200 got ${status}"
  exit 1
fi

# expect the response we just sent to be found
num_responses=$(find $RESPONSES_DIR -mmin -${RESPONSE_MAX_AGE_MINUTES} -name '*' -type f | wc -l)
if [ "${num_responses}" != "0" ]; then
  echo "Response submitted successfully"
  exit 0
fi
echo "Response was not output as expected"
exit 1
