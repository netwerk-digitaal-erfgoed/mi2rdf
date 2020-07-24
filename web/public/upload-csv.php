<?php
 
include('includes/config.php');
include('includes/database.php');

set_time_limit(0);

if (isset($_SESSION["organisation_id"])) {
	$organisation_id=$_SESSION["organisation_id"];
} else {
	error_log("WARN: functie alleen voor ingelogde gebruikers");
	exit;
}

if (!is_dir(UPLOAD_DIR)) {
	mkdir(UPLOAD_DIR);
}
		
if ($_FILES["file"]["size"]>0) {
	$uploadedfile=basename($_FILES['file']['name']);
	if ($uploadedfile!="") {
		$guid=GUID();
		if (move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_DIR.$guid.$ext)) {
			error_log("INFO: file uploaded ".UPLOAD_DIR.$guid.$ext);
			insert_csv(UPLOAD_DIR.$guid.$ext,$organisation_id);
		} else {
			error_log("WARN: Possible file upload attack $uploadedfile!");
		}
	} else {
		error_log("ERROR: Geen bestand ge-upload");
	}
} else {
	error_log("ERROR: bestandsupload mislukt");
}

header("Location: /mi2rdf/");
exit;

function GUID() {
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function insert_csv($filename,$organisation_id) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);

	if ($mysqli->connect_error) {
		error_log("ERROR: Connection failed: " . $mysqli->connect_error);
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	}

	$sql = "INSERT IGNORE INTO id2guid(adt_id,id,GUID) VALUES ($organisation_id,?,?)";
	$stmt = $mysqli->prepare($sql);
	if (!$stmt) {
		error_log($mysqli->errno.' '.$mysqli->error);
	} else {
		$file = fopen($filename, "r");
		while (($csvData = fgetcsv($file, 10000, ",")) !== FALSE) {
			$id=intval($csvData[0]);
			$guid=strtoupper(preg_replace("/[^A-Za-z0-9]/",'',$csvData[1]));
			if(strlen($guid)==32 && $id>0) {
				$stmt->bind_param("is",$csvData[0],$csvData[1]);
				$stmt->execute();
			} else {
				error_log("WARN: fout in aangeleverd id ($csvData[0]) en/of guid ($csvData[1])");
			}
		}
		fclose($file);
		$mysqli->close();
		error_log("INFO: CSV File has been successfully Imported.");
	}
}