<?php

function fInsertDataset($guid,$org_name,$state="uploaded") {
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);

	if ($mysqli->connect_error) {
		error_log("ERROR: Connection failed: " . $mysqli->connect_error);
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 

	$sql = "INSERT INTO datasets(guid,org_name,state) VALUES (?,?,?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sss",$guid,$org_name,$state);
	$stmt->execute();

	if (mysqli_query($mysqli, $sql)) {
		error_log("INFO: New record created successfully");
	} else {
		error_log("ERROR: " . $sql . "" . mysqli_error($mysqli));
	}
	$mysqli->close();
}

function fDeleteDataset($guid) {
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);
	if ($mysqli->connect_error) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 

	$sql = "DELETE FROM datasets WHERE guid=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("s",$guid);
	$stmt->execute();

	$mysqli->close();
}

function arrGetDataset($guid) {
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql = "SELECT * FROM datasets WHERE guid=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("s",$guid);
	$stmt->execute();

	if (!$result = $stmt->get_result()) {
		error_log("Error: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		$dataset = $result->fetch_assoc();
		$result->free();
	}
	$mysqli->close();
	return $dataset;
}

function arrGetDatasets($last=5) {
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql = "SELECT * FROM datasets ORDER BY id DESC LIMIT 0,".$last;
	$datasets=array();	
	if (!$result = $mysqli->query($sql)) {
		error_log("ERORR: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		while ($dataset = $result->fetch_assoc()) {
			array_push($datasets,$dataset);
		}
		$result->free();
	}
	$mysqli->close();
	return $datasets;
}