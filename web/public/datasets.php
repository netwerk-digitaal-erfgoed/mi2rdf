<?php
 
include('includes/config.php');
include('includes/database.php');

$datasets=arrGetDatasets(10);
echo json_encode($datasets);