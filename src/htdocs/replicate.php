<?php

// replicate_incoming
//
// A script to take raw DYFI entry files (created by
// response.php) and copy them to different directories
// for each DYFI backend server.

function replicate($ini,$source) {
	if (!file_exists($source)) {
		return;
	}

	// These directories should have been created by pre-install.
	$servers = explode(',',$ini['BACKEND_SERVERS']);
	$data_dir = $ini['WRITE_DIR'];
	$input_dir = $data_dir . "/incoming";
	$out_template = $data_dir . "/incoming.SERVER";

	$filename = basename($source);
	
	foreach ($servers as $server) {
		$dest = str_replace('SERVER',$server,$out_template);
		$dest .= "/" . $filename;
		copy($source,$dest);
	}

	// Move the original file to the backup directory, but keep timestamp
	$dt = filemtime($source);
	$backup = str_replace('SERVER','backup',$out_template);
	$dest = $backup . "/" . $filename;
	rename($source,$dest); 
	touch($dest,$dt);
}

?>

