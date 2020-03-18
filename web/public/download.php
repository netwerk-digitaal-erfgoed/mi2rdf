<?php

include('includes/config.php');

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	$file=$guid.".ttl";
	if(file_exists(UPLOAD_DIR.$file)) {
		send_file(UPLOAD_DIR,$file);		
	} else {
		echo "$file doesn't exist";
	}
} else {
	echo "invalid guid";
}

function send_file($dir,$filename) {

	if($fn=fopen($dir.$filename,'r')) {
		if ($bgzip>0) {
			header('Content-Encoding: gzip');
		}
		header("Content-Length: ".filesize($dir.$filename));
		header("Content-Type: text/turtle");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Connection: close');
		#sleep(1);
		fpassthru($fn);
		exit;
	}
}

