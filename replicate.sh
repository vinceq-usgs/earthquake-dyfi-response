#!/bin/sh

HOME_DIR=[apps]/earthquake-dyfi-response/replicate

# An example shell script to run the replicate program. 
# Run this from the crontab (preferably, once every minute.)

$HOME_DIR/replicate_incoming >> $HOME_DIR/replicate.log &
