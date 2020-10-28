<?php
 
include('includes/config.php');
include('includes/database.php');

$organisation_id=0;
if (isset($_SESSION["organisation_id"])) {
	$organisation_id=$_SESSION["organisation_id"];

	if (isset($_POST["context"])) {
		file_put_contents("/filestore/".$organisation_id."/context.json",$_POST["context"]);
	}

}

header("Location: /");
exit;