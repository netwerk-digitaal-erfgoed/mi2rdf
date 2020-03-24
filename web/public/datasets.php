<?php
 
include('includes/config.php');
include('includes/database.php');

$datasets=arrGetDatasets(200);

header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
echo json_encode($datasets);