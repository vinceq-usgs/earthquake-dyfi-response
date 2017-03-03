#!/bin/sh

# An example shell script to run the replicate program. 
# Run this from the crontab (preferably, once every minute.)

HOME_DIR=[apps]/earthquake-dyfi-response/.build/src/replicate
cd $HOME_DIR; /usr/local/bin/php $HOME_DIR/replicate_incoming.php >> $HOME_DIR/replicate.log &
