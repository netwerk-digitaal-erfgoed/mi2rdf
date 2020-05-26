<?php
 
include('includes/config.php');
include('includes/database.php');

unset($_SESSION["organisation_id"]);
unset($_SESSION["user"]);

header("Location: .");
exit;