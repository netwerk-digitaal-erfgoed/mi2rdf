<?php

include('includes/config.php');
include('includes/database.php');

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	if (strlen($guid)==36) {
		fDeleteDataset($guid);
		$files = glob(UPLOAD_DIR.$guid.'*');
		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
			}
		}
	}
} else {
	echo "ERROR: invalid guid";
}