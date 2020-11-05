<?php

include('includes/config.php');

$type="ttl";
if (isset($_GET["type"]) && ($type=="ttl" || $type=="json" || $type=="src")) {
	$type=$_GET["type"];
}

$org_id=0;
if (isset($_GET["org"])) {
	$org_id=intval($_GET["org"]);
}

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	
	if ($type=="src") {
		$ext="txt";
	} else { 
		$ext=$type;
	}
	
	$file=$guid.".".$ext;
	if(file_exists(UPLOAD_DIR.$org_id.'/'.$file)) {
		send_file(UPLOAD_DIR.$org_id.'/',$file,$ext);		
	} else {
		if ($type=="src") {
			$ext="xml";
			$file=$guid.".".$ext;
			if(file_exists(UPLOAD_DIR.$org_id.'/'.$file)) {
				send_file(UPLOAD_DIR.$org_id.'/',$file,$ext);		
			} else {
				echo "ERROR: $file doesn't exist";
			}
		} else {
			echo "ERROR: $file doesn't exist";
		}
	}
} else {
	echo "ERROR: invalid guid";
}

function send_file($dir,$filename,$ext) {
	
	$contenttypes=array( 
		"txt"=>"text/plain", 
		"xml"=>"text/xml", 
		"json"=>"application/json", 
		"ttl"=>"text/turtle"
	);
	
	if($fn=fopen($dir.$filename,'r')) {
		header("Content-Length: ".filesize($dir.$filename));
		header("Content-Type: ".$contenttypes[$ext]);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Connection: close');
		fpassthru($fn);
		exit;
	}
}