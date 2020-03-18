<?php

function fInsertDataset($guid,$org_name,$state="uploaded") {
	error_log("DEBUG: fInsertDataset($guid,$org_name,$state)");
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);
	if ($mysqli->connect_error) {
	   die("Connection failed: " . $mysqli->connect_error);
	} 

	$sql = "INSERT INTO datasets(guid,org_name,state) VALUES (?,?,?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sss",$guid,$org_name,$state);
	$stmt->execute();

	if (mysqli_query($mysqli, $sql)) {
	   echo "New record created successfully";
	} else {
	   echo "Error: " . $sql . "" . mysqli_error($mysqli);
	}
	$mysqli->close();

}

function arrGetDatasets($last=5) {
	$mysqli = new mysqli(DB_HOST, DB_PASS, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
	   die("Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql = "SELECT * FROM datasets ORDER BY id DESC LIMIT 0,".$last;
	$datasets=array();	
	if (!$result = $mysqli->query($sql)) {
		echo "Error: " . $sql . "" . mysqli_error($mysqli);
	} else {	
		while ($dataset = $result->fetch_assoc()) {
			array_push($datasets,$dataset);
		}
		$result->free();
	}
	$mysqli->close();
	return $datasets;
}