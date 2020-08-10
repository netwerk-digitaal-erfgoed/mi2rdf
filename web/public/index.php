<?php
 
include('includes/config.php');
include('includes/database.php');

$organisation_id=0;
if (isset($_SESSION["organisation_id"])) {
	$organisation_id=$_SESSION["organisation_id"];
}
$_SESSION["organisation"]=arrGetOrganisationInfo($organisation_id);

?><!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LODwrapper om MDWS Internet bestanden te converteren naar triples.">
    <link href="./assets/imgs/nde_logo_simplified.png" rel="icon" type="image/png">
    <title>MI2RDF</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <link href="./assets/css/main.css?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>" rel="stylesheet" type="text/css">
    <link href="./assets/css/baton.css?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>" rel="stylesheet" type="text/css">
</head>

<body class="withNavbar withSink">
    <nav class="navbar fixed-top ">
        <div class="navbar-content">
            <div class="navbar-icon">
                <a class="navbar-brand" href=".">
                    <img alt="MI2RDF" src="./assets/imgs/nde_logo.png">
                </a>
            </div>
            <div class="navbar-title">
                <span data-toggle="tooltip" data-placement="right" data-html="true" title="<b>Gebruikte componenten</b>:<br>MDWS-JSON-to-Turtle versie <?= file_get_contents("/filestore/MDWS-JSON-to-Turtle.dat") ?><br>MF-Export-XML-to-JSON versie <?= file_get_contents("/filestore/MF-Export-XML-to-JSON.dat") ?><br>MDWS-to-JSON versie <?= file_get_contents("/filestore/MDWS-to-JSON.dat") ?>">MI2RDF <?= $_SERVER['ASSETS_CACHEBUSTER'] ?></span>
            </div>
        </div>
		<div style="float:right">
		<?php if (!isset($_SESSION["user"])) { ?>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Inloggen</button>
		<?php } else { ?>
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#configModal">Instellingen</button>
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#id2guidModal">ID-GUID tabel</button>
		<a href="uitloggen.php" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ingelogd als <?php echo $_SESSION["user"]." (".htmlentities($_SESSION["organisation"]["name"]).")" ?>">Uitloggen</a>
		<?php } ?>
		</div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col headcol">
                <h1>MI2RDF<?php if (isset($_SESSION["user"])) { echo ' - '.htmlentities($_SESSION["organisation"]["name"]); } ?></h1>
                <p>LODwrapper om MDWS Internet bestanden te converteren naar triples.</p>
            </div>
        </div>

        <div class="row" id="drop-area1">
            <div class="col sink storyBanner">
				<div id="uploadprogress"></div>
				<div id="drop-area2">
					<p>Sleep hier MDWS Internet (txt) of MF Export (xml) bestanden heen of klik op onderstaande knop om bestanden te selecteren.</p>
					<form class="my-form">
						<input type="file" multiple id="fileElem" accept=".txt,.zip" onchange="handleFiles(this.files)">
						<label data-toggle="tooltip" data-placement="bottom" title="Maximale grootte per bestand is 500MB. De bestanden kunnen ook gecomprimeerd en/of gebundeld worden in een .zip bestand." class="btn btn-block btn-label" for="fileElem">Selecteer bestand(en)</label>
					</form>
					Het vertrippelen naar de<br><a target="triply" href="https://data.netwerkdigitaalerfgoed.nl/<?= htmlentities($_SESSION["organisation"]["triply_user"],ENT_QUOTES).'/'.htmlentities($_SESSION["organisation"]["triply_dataset"],ENT_QUOTES); ?>">NDE triplestore</a> start direct.
				</div>
				<div id="midelbar">
					<div id="koppelstuk"></div>
	                <div class="baton-container">
						<div class="baton-0">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-1">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-2">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-3">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-4">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-5">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-6">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-7">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-8">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-9">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-10">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-11">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-12">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-13">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-14">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-15">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-16">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-17">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-18">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-19">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-20">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-21">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-22">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-23">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-24">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-25">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-26">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-27">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-28">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-29">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-30">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-31">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-32">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-33">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-34">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
						<div class="baton-35">
							<div class="metronome">
								<div class="baton"></div>
							</div>
						</div>
					</div>

				</div>
				<div id="datasetlist"></div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalTitle" aria-hidden="true">
	  <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="downloadModalTitle">Download als ...</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body">
		  <ul>
		  <li><a id="dsrc" href="#">Bron bestand</a></li>
		  <li><a id="djson" href="#">JSON bestand</a></li>
		  <li><a id="dttl"  href="#">Turtle bestand</a></li>
		  </ul>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Sluiten</button>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="logModalTitle">Logging</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Sluiten"><span aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body" id="logModalBody">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Sluiten</button>
		  </div>
		</div>
	  </div>
	</div>
<?php if (!isset($_SESSION["user"])) { ?>
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="loginModalTitle">Inloggen</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Sluiten"><span aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body" id="loginModalBody">
		  <form action="inloggen.php" method="post">
		  <label for="username">Gebruikersnaam</label><input class="form-control" id="username" name="username" required>
		  <label for="pass">Wachtwoord</label> <input class="form-control" type="password" id="pass" name="password" minlength="8" required>
		  <br><input type="submit" class="btn btn-primary" value="Inloggen">
		  </form>
		  </div>
		</div>
	  </div>
	</div>
<?php } else { ?>
	<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="configModalTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="configModalTitle">Instellingen</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Sluiten"><span aria-hidden="true">&times;</span></button>
		  </div>
		  <div class="modal-body" id="configModalBody">
		  <form action="instellingen.php" method="post">
		  
		  <p>Onderstaande instellingen zijn voor alle gebruikers die gekoppeld zijn aan <strong><?= htmlentities($_SESSION["organisation"]["name"]) ?></strong>.</p>
		  <label for="namespace">Base URL</label>
		   <span class="btn btn-sm btn-warning float-right" data-html="true" data-toggle="tooltip" data-placement="top" data-original-title="<p>mi2rdf zal deze <b>base URL</b> gebruiken als namespace voor de URI's van de triples.">?</span>
		  <input class="form-control" value="<?= htmlentities($_SESSION["organisation"]["namespace"],ENT_QUOTES) ?>" id="namespace" name="namespace" required>
		  <p><br></p>
		  <label for="tuser">Triply (organization) user</label>
		  <span class="btn btn-sm btn-warning float-right" data-html="true" data-toggle="tooltip" data-placement="top" data-original-title="<p>mi2rdf zal de triples opslaan in Triply, in een graph binnen een specifieke dataset van je organisatie.</p><ul><li>Login op data.netwerkdigitaalerfgoed.nl (Triply via NDE);</li><li>Klik je gebruikersnaam, rechtboven, en kies <b>My account</b>;</li><li>Klik op je organisatie (onder <b>Organizations</b>);</li><li>Linksboven staat de naam van je organisatie, kopieer deze waarde en plak het hier bij de Instellingen in het veld <b>Triply (organization) user</b>.</li></ul><p>NB: je kunt ook je eigen gebruikersnaam gebruiken, maar de organisatie is netter.">?</span>
		  <input class="form-control" type="text" value="<?= htmlentities($_SESSION["organisation"]["triply_user"],ENT_QUOTES) ?>" id="tuser" name="tuser" required>
		  <label for="ttoken">Triply token</label>
		  <span class="btn btn-sm btn-warning float-right" data-html="true" data-toggle="tooltip" data-placement="top" data-original-title="<p>mi2rdf zal de triples opslaan in Triply, hiervoor dient mi2rdf toegang te krijgen.</p><ul><li>Login op data.netwerkdigitaalerfgoed.nl (Triply via NDE);</li><li>Klik je gebruikersnaam, rechtboven, en kies <b>User settings</b>;</li><li>Klik op <b>API TOKENS</b>;</li><li>Klik op <b>+ Create token</b>;</li><li>Vul een <b>Token name</b> in (bijv. mi2rdf) en zorg dat zowel <b>Read access</b> als <b>Write access</b> zijn geselecteerd, klik op <b>Create</b>;</li><li>Kopieer het aangemaakte token en plak het hier bij de Instellingen in het veld <b>Triply Token</b>.</li></ul>">?</span>
		  <input class="form-control" type="text" value="<?= htmlentities($_SESSION["organisation"]["triply_token"],ENT_QUOTES) ?>" id="ttoken" name="ttoken" minlength="255" required>
		  <label for="tdataset">Triply dataset</label>
		  <span class="btn btn-sm btn-warning float-right" data-html="true" data-toggle="tooltip" data-placement="bottom" data-original-title="<p>mi2rdf zal de triples opslaan in Triply, in een graph binnen een specifieke dataset van je organisatie.</p><ul><li>Login op data.netwerkdigitaalerfgoed.nl (Triply via NDE);</li><li>Klik je gebruikersnaam, rechtboven, en kies <b>My account</b>;</li><li>Klik op je organisatie (onder <b>Organizations</b>);</li><li>Klik op <b>Add dataset</b>;</li><li>Vul een 'Dataset name' in (bijv. mi2rdf), kopieer deze waarde en plak het hier bij de Instellingen in het veld <b>Triply Dataset</b>;</li><li>Vul eventueel de <b>Display name</b> en <b>Description</b> in, kies de gewenste zichtbaarheid (<b>Public</b> is een goede waarde, het gaat immers om open data).</li></ul>">?</span>
		  <input class="form-control" type="text" value="<?= htmlentities($_SESSION["organisation"]["triply_dataset"],ENT_QUOTES) ?>" id="tdataset" name="tdataset" required>
		
		  <br><input type="submit" class="btn btn-primary" value="Opslaan">
		  </form>
		  </div>
		</div>
	  </div>
	</div>
	<div class="modal fade" id="id2guidModal" tabindex="-1" role="dialog" aria-labelledby="id2guidModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="id2guidModalTitle">ID-GUID tabel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Sluiten"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body" id="id2guidModalBody">
					<form action="upload-csv.php" method="post" enctype="multipart/form-data">
						<p>De ID-GUID tabel voor <strong><?= htmlentities($_SESSION["organisation"]["name"]) ?></strong> bevat <?=  nrID2GUIDtabel($organisation_id) ?> regels. Deze waarden wordt gebruikt bij de vertaling van ID's naar GUID's in de te genereren linked data.</p>
						<hr>
						<p>De tabel kan gevuld worden door het uploaden van een CSV bestand (met als extensie .csv). Het CSV bestand kan ook gecomprimeerd worden en als .zip aangeboden worden. Elke regel in het CSV bestand moet een ID en een GUID bevatten, gescheiden door een komma. Een GUID moet bestaan uit 32 tekens (0..9, A..F, geen koppeltekens). Bijvoorbeeld:</p>
<pre>2853004,0D8F45A06E7542C7A7E563806DD83394
5651440,210B29A49D3B81CB44A430B64F90A6DE
5651441,A3EDC9ED4F854135885119BAEA30E93F
6086160,AC22AA21DBAA41E38B4A50C498D91D9E
6086161,D4D9772AD1264345BBF591A7C1BAD0CB</pre>
						<label for="namespace">Nieuw CSV bestand</label>
						<input class="form-control" type="file" accept=".csv,.zip" id="file" name="file" required>
						<br><input type="submit" class="btn btn-primary" value="Uploaden">
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
	<script>
	var triply_user='<?= htmlentities($_SESSION["organisation"]["triply_user"],ENT_QUOTES) ?>';
	var triply_dataset='<?= htmlentities($_SESSION["organisation"]["triply_dataset"],ENT_QUOTES) ?>';
	</script>
    <script src="./assets/js/main.js?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>"></script>
</body>
</html>