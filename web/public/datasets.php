<?php
 
include('includes/config.php');
include('includes/database.php');

$organisation_id=0;
if (isset($_SESSION["organisation_id"])) {
	$organisation_id=$_SESSION["organisation_id"];
}

$datasets=arrGetDatasets($organisation_id, MAX_LIST);

header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
echo json_encode($datasets);