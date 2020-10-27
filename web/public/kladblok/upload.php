<?php

include('../includes/config.php');
include('../includes/database.php');
include('../includes/queue.php');

$organisation_id=0;
if (isset($_SESSION["organisation_id"])) {
	$organisation_id=$_SESSION["organisation_id"];
} else {
	header("Location: /");
	exit;
}

if (isset($_POST["kladblok"])) {
	$guid=md5($organisation_id);
	$file='/filestore/'.$guid.".kladblok.ttl";
	file_put_contents($file,$_POST["kladblok"]);
	fAddToQueue($guid,"kladblok",$organisation_id);
	error_log("INFO: $file stored en queued for upload to kladblok graph");
} else {
	error_log("ERROR: er mist kladblok inhoud");
}
header("Location: /kladblok/");
exit;