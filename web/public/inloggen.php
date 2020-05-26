<?php
 
include('includes/config.php');
include('includes/database.php');


$username=$_POST["username"];
$password_hash=hash("sha256",$_POST["password"]);

$organisation_id=nrLoginUser($username,$password_hash);

if (isset($organisation_id) && $organisation_id>0) {
	$_SESSION["user"]=$username;
	$_SESSION["organisation_id"]=$organisation_id;
} else {
	unset($_SESSION["user"]);
	unset($_SESSION["organisation_id"]);
}

header("Location: .");
exit;
