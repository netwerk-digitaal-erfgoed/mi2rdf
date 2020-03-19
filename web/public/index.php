<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LODwrapper om MDWS Internet bestanden te converteren naar triples.">
    <link href="./assets/imgs/nde_logo_simplified.png" rel="icon" type="image/png">
    <title>MI2RDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="./assets/css/main.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/baton.css" rel="stylesheet" type="text/css">
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
                <a class="navbar-brand" href=".">MI2RDF</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col" style="text-align:center;">
                <h1>MI2RDF</h1>
                <p>LODwrapper om MDWS Internet bestanden te converteren naar triples.</p>
                <p><br><br></p>
            </div>
        </div>

        <div class="row">
            <div class="col sink storyBanner">

                <table width="100%" border=0>
                    <tr>
                        <td id="drop-area" rowspan="3" width="25%">
                            <p>Sleep hier &eacute;&eacute;n of meerdere MDWS Internet bestanden heen of klik op onderstaande knop om bestanden te selecteren.</p>
                            <form class="my-form">
                                <input type="file" multiple id="fileElem" accept=".txt,.zip" onchange="handleFiles(this.files)">
                                <label title="Maximale grootte per bestand is 100MB. De .txt bestanden kunnen ook gecomprimeerd en/of gebundeld worden in een .zip bestand." class="btn btn-block btn-label" for="fileElem">Selecteer bestand(en)</label>
                            </form>
							<br>Het vertrippelen start direct.
                        </td>
                        <td width="30%"><br></td>
                        <td id="datasetlist" width="45%" rowspan="3"><br></td>
                    </tr>
                    <tr>
                        <td id="midelbar"><br></td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                        </td>
                    </tr>
                </table>

                <div class="baton-container" style="position:absolute;left:27%;top:0px;">
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
        </div>
    </div>

    <script src="./assets/js/main.js"></script>
</body>

</html>