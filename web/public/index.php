<!DOCTYPE html>
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
                <span  data-toggle="tooltip" data-placement="right" data-html="true" title="<b>Gebruikt componenten</b>:<br>MDWS-JSON-to-Turtle versie <?= file_get_contents("/filestore/MDWS-JSON-to-Turtle.dat") ?><br>MDWS-to-JSON <?= file_get_contents("/filestore/MDWS-to-JSON.dat") ?>">MI2RDF <?= $_SERVER['ASSETS_CACHEBUSTER'] ?></span>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col headcol">
                <h1>MI2RDF</h1>
                <p>LODwrapper om MDWS Internet bestanden te converteren naar triples.</p>
            </div>
        </div>

        <div class="row" id="drop-area1">
            <div class="col sink storyBanner">
				<div id="uploadprogress"></div>
				<div id="drop-area2">
					<p>Sleep hier &eacute;&eacute;n of meerdere MDWS Internet bestanden heen of klik op onderstaande knop om bestanden te selecteren.</p>
					<form class="my-form">
						<input type="file" multiple id="fileElem" accept=".txt,.zip" onchange="handleFiles(this.files)">
						<label data-toggle="tooltip" data-placement="bottom" title="Maximale grootte per bestand is 500MB. De .txt bestanden kunnen ook gecomprimeerd en/of gebundeld worden in een .zip bestand." class="btn btn-block btn-label" for="fileElem">Selecteer bestand(en)</label>
					</form>
					Het vertrippelen naar de<br><a target="triply" href="https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf">(demo) triplestore</a> start direct.
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
		  <li><a id="dtxt" href="#">Tekst bestand</a></li>
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

    <script src="./assets/js/main.js?<?= $_SERVER['ASSETS_CACHEBUSTER'] ?>"></script>
</body>

</html>