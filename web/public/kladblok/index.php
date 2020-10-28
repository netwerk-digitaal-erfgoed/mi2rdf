<?php

include('../includes/config.php');
include('../includes/database.php');

$organisation_id=0;
if (isset($_SESSION["organisation_id"])) {
        $organisation_id=$_SESSION["organisation_id"];
} else {
    header("Location: /");
    exit;
}

$_SESSION["organisation"]=arrGetOrganisationInfo($organisation_id);

$kladblok="";
$btriply=0;
if (!empty($_SESSION["organisation"]["triply_token"]) && !empty($_SESSION["organisation"]["triply_user"])) {
	$btriply=1;
	$file='/filestore/'.md5($organisation_id).".kladblok.ttl";
	if (file_exists($file)) {
		$kladblok=file_get_contents($file);
	}
}	
	

?><!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LODwrapper om MDWS Internet bestanden te converteren naar triples.">
    <link href="../assets/imgs/nde_logo_simplified.png" rel="icon" type="image/png">
    <title>MI2RDF</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <link href="../assets/css/main.css?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>" rel="stylesheet" type="text/css">
    <link href="../assets/css/baton.css?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>" rel="stylesheet" type="text/css">
</head>

<body class="withNavbar withSink">
    <nav class="navbar fixed-top ">
        <div class="navbar-content">
            <div class="navbar-icon">
                <a class="navbar-brand" href="..">
                    <img alt="MI2RDF" src="../assets/imgs/nde_logo.png">
                </a>
            </div>
            <div class="navbar-title">
                <span data-toggle="tooltip" data-placement="right" data-html="true" title="<b>Gebruikte componenten:</b><br>MFXML-to-JSONLD versie <?= file_get_contents("/filestore/MFXML-to-JSONLD.dat") ?>">MI2RDF <?= $_SERVER['ASSETS_CACHEBUSTER'] ?></span>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col headcol">
                <h1>MI2RDF - Triple kladblok - <?= htmlentities($_SESSION["organisation"]["name"]); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
			<?php if ($btriply==1) { ?>
             <form action="upload.php" method="post"><textarea name="kladblok" style="width:100%;height:400px;font-family: courier;font-size: 0.9em;padding: 10px;"><?= htmlentities($kladblok,ENT_QUOTES) ?></textarea><br><input type="submit" class="btn btn-success" value="Opslaan (in kladblok graph in Triply)"></form>
			<?php } else { ?>
			<p class="bg-danger">Er is nog geen Triply instantie geconfigureerd (dit kan via de Instellingen knop).</p>
			<?php } ?>
            </div>
        </div>
    </div>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</body>
</html>