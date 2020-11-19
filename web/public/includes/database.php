<?php

function fInsertDataset($guid,$org_name,$state="uploaded",$organisation_id) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);

	if ($mysqli->connect_error) {
		error_log("ERROR: Connection failed: " . $mysqli->connect_error);
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 

	$sql = "INSERT INTO datasets(guid,org_name,state,organisation_id) VALUES (?,?,?,?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ssss",$guid,$org_name,$state,$organisation_id);
	$stmt->execute();
	$mysqli->close();
}
		
function fUpdateOrganisation($organisation_id,$namespace,$namespaceid,$namespacedef,$tuser,$ttoken,$tdataset) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);

	if ($mysqli->connect_error) {
		error_log("ERROR: Connection failed: " . $mysqli->connect_error);
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 

	$sql = "UPDATE organisations SET namespace=?, namespaceid=?, namespacedef=?, triply_user=?, triply_token=?, triply_dataset=? WHERE id=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ssssssd",$namespace,$namespaceid,$namespacedef,$tuser,$ttoken,$tdataset,$organisation_id);
	$stmt->execute();
	$mysqli->close();
}


function fDeleteDataset($guid) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
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
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
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

function arrGetDatasets($organisation_id, $last=5) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql = "SELECT * FROM datasets WHERE organisation_id=? ORDER BY id DESC LIMIT 0,".$last;
	$stmt = $mysqli->prepare($sql);
	if ( false===$stmt ) { error_log('FAIL: prepare() failed: ' . $mysqli->error); }
	$stmt->bind_param("d",$organisation_id);
	$stmt->execute();
	
	$datasets=array();	
	if (!$result = $stmt->get_result()) {
		error_log("ERROR: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		while ($dataset = $result->fetch_assoc()) {
			array_push($datasets,$dataset);
		}
		$result->free();
	}
	$mysqli->close();
	return $datasets;
}

function nrLoginUser($username,$password_hash) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql="SELECT organisation_id FROM users WHERE username=? AND password_hash=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ss",$username,$password_hash);
	$stmt->execute();

	if (!$result = $stmt->get_result()) {
		error_log("Error: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		if ($user = $result->fetch_assoc()) {
			$org_id=$user['organisation_id'];
		}
		$result->free();
	}
	$mysqli->close();
	return $org_id;	
}

function arrGetOrganisationInfo($org_id) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql = "SELECT * FROM organisations WHERE id=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("s",$org_id);
	$stmt->execute();

	if (!$result = $stmt->get_result()) {
		error_log("Error: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		$organisation = $result->fetch_assoc();
		$result->free();
	}
	$mysqli->close();
	return $organisation;
}


function nrID2GUIDtabel($organisation_id) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATA);
	if ($mysqli->connect_errno) {
		die("ERROR: Connection failed: " . $mysqli->connect_error);
	} 
	
	$sql="SELECT COUNT(*) AS aantal FROM id2guid WHERE adt_id=?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i",$organisation_id);
	$stmt->execute();

	$aantal=0;
	if (!$result = $stmt->get_result()) {
		error_log("Error: " . $sql . "" . mysqli_error($mysqli));
	} else {	
		if ($res = $result->fetch_assoc()) {
			$aantal=$res['aantal'];
		}
		$result->free();
	}
	$mysqli->close();
	return $aantal;	
}
