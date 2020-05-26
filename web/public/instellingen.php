<?php
 
include('includes/config.php');
include('includes/database.php');

if (isset($_SESSION["organisation_id"])) {
	if (isset($_POST["namespace"]) && isset($_POST["tuser"]) && isset($_POST["ttoken"]) && isset($_POST["tdataset"])) {
		
		$namespace=$_POST["namespace"];
		$tuser=$_POST["tuser"];
		$ttoken=$_POST["ttoken"];
		$tdataset=$_POST["tdataset"];
		
		$organisation_id=$_SESSION["organisation_id"];

		fUpdateOrganisation($organisation_id,$namespace,$tuser,$ttoken,$tdataset);
		
		$_SESSION["organisation"]["namespace"]=$namespace;
		$_SESSION["organisation"]["triply_user"]=$tuser;
		$_SESSION["organisation"]["triply_token"]=$ttoken;
		$_SESSION["organisation"]["triply_dataset"]=$tdataset;

	} else {
		error_log("ERROR: not all required field in POST");
	}
} else {
	error_log("ERROR: call to instellingen without organisation_id set (not logged in)");
}

header("Location: .");
exit;