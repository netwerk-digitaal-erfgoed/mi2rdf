<?php

include('../includes/config.php');

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	$file=$guid.".kladblok.ttl";
	send_file('/filestore/',$file);
} else {
	echo "ERROR: invalid guid";
}

function send_file($dir,$filename) {
	if(file_exists($dir.$file)) {
		if($fn=fopen($dir.$filename,'r')) {
			header("Content-Length: ".filesize($dir.$filename));
			header("Content-Type: text/turtle");
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Pragma: no-cache');
			header('Connection: close');
			fpassthru($fn);
			exit;
		}
	} else {
		echo "ERROR: file $dir$file doesn't exist";
	}
}
