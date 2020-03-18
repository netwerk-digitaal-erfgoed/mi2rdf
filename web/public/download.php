<?php

include('includes/config.php');

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	$file=$guid.".ttl";
	if(file_exists(UPLOAD_DIR.$file)) {
		send_file(UPLOAD_DIR,$file);		
	} else {
		echo "ERROR: $file doesn't exist";
	}
} else {
	echo "ERROR: invalid guid";
}

function send_file($dir,$filename) {
	if($fn=fopen($dir.$filename,'r')) {
		header("Content-Length: ".filesize($dir.$filename));
		header("Content-Type: text/turtle");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Connection: close');
		fpassthru($fn);
		exit;
	}
}