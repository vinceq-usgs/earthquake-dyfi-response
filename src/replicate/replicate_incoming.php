<?php

// replicate_incoming
//
// A script to take raw DYFI entry files (created by
// response.php) and copy them to different directories
// for each DYFI backend server.
//
// Usage: This script will run once; it is meant to be run 
// periodically from the crontab, preferably every minute.

include_once '../conf/config.inc.php';

$servers = explode(',',$CONFIG['BACKEND_SERVERS']);

// These directories should have been created by pre-install.
$data_dir = $CONFIG['DATA_DIR'];
$log_dir = $CONFIG['LOG_DIR'];
$input_dir = $data_dir . "/incoming";
$out_template = $data_dir . "/incoming.SERVER";

$files = glob("$input_dir/entry.*");
if (!$files) {
  exit;
}

$timestamp = date('Y-m-d H:i:s');
$nresp = sizeof($files);
echo "replicate_incoming starting: $timestamp with $nresp entries\n";

foreach ($files as $source) {
  foreach ($servers as $server) {
    $dest = str_replace('SERVER',$server,$out_template);
    echo "Basename is " . basename($source) . "\n";
    $dest .= "/" . basename($source);
    echo "Sending $source to $dest.\n";
    copy($source,$dest);
  }

  // This will move the original file, but keep timestamp
  $dt = filemtime($source);
  $backup = str_replace('SERVER','backup',$out_template);
  $dest = $backup . "/" . basename($source);
  rename($source,$dest); 
  touch($dest,$dt);
}

?>

