var myTimer;
var bUpdateDatasetlist = 1;

let dropArea = document.getElementById('drop-area1');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false)
    document.body.addEventListener(eventName, preventDefaults, false)
});

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false)
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false)
});

dropArea.addEventListener('drop', handleDrop, false);

updateDatasetlist();


function preventDefaults(e) {
    e.preventDefault()
    e.stopPropagation()
}

function highlight(e) {
    document.getElementById('drop-area2').classList.add('highlight')
}

function unhighlight(e) {
    document.getElementById('drop-area2').classList.remove('highlight')
}

function handleDrop(e) {
    e.preventDefault();
    var files = e.dataTransfer.files
    for (var i = 0; i < files.length; i++) {
        uploadFile(files[i]);
    }
}

function handleFiles(files) {
    for (var i = 0; i < files.length; i++) {
        uploadFile(files[i]);
    }
}


function uploadFile(file) {
    var filelength = parseInt(file.name.length) - 3;
    var fileext = file.name.substring(filelength, filelength + 3);
    if (fileext.toLowerCase() != "txt" && fileext.toLowerCase() != "zip") {
        MsgBox("U kunt alleen een MAIS internet bestand uploaden (herkenbaar aan de bestandsextensie .txt) of een of meer gecomprimeerde MAIS internet bestanden in één ZIP bestand.", "Foutmelding");
        return;
    }

    var url = 'upload.php';
    var xhr = new XMLHttpRequest();
    var formData = new FormData();
    xhr.open('POST', url, true);
    xhr.addEventListener('readystatechange', function(e) {
        if (xhr.readyState == 4 && xhr.status == 200) {
            bUpdateDatasetlist = 1;
            updateDatasetlist();
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            // Error. Inform the user
        }
    })
    formData.append('file', file);
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            document.getElementById("uploadprogress").style.height=Math.ceil(240*e.loaded/e.total)+"px";
        }
    }
    xhr.onloadstart = function(e) {
        document.getElementById("uploadprogress").style.height="0px";
        document.getElementById("uploadprogress").style.width="8px";
        document.getElementById("uploadprogress").style.marginLeft="0px";
    }
    xhr.onloadend = function(e) {
        document.getElementById("uploadprogress").style.width="3px";
        document.getElementById("uploadprogress").style.marginLeft="5px";
    }
    xhr.send(formData);
}

function updateDatasetlist() {

    clearTimeout(myTimer);
    var url = 'datasets.php'
    var req = new XMLHttpRequest();
    req.responseType = 'json';
    req.open('GET', url, true);
	
	req.onload = function() {
        if (req.response) {
            var jsonResponse = req.response;
            var listDiv = "<ul class='dlist'>";
            var bUnconverted = 0;
			var namecutoff=32;
            for (var i = 0; i < jsonResponse.length; i++) {
                listDiv += '<li id="dataset_' + jsonResponse[i].guid + '" title="Originele bestandsnaam: ' + jsonResponse[i].org_name + ' | Aangeleverd: ' + jsonResponse[i].created + ' | Omgezet: ' + jsonResponse[i].converted + '">';
                listDiv += '<span class="nrid">'+jsonResponse[i].id + '</span> ';
				if (jsonResponse[i].org_name.length<namecutoff) {
					listDiv += jsonResponse[i].org_name;
				} else {
					listDiv += jsonResponse[i].org_name.substr(0,namecutoff-3)+'&hellip;';
				}
				
                listDiv += '<a class="lstbtn" title="Verwijder deze dataset" href="#" onclick="deldataset(\'' + jsonResponse[i].guid + '\')"><img height="16" src="assets/imgs/trash.svg"></a>';
                if (jsonResponse[i].state == 'converted') {
                    listDiv += '<a class="lstbtn" title="Downoad deze dataset (in Turtle formaat)" href="download.php?guid=' + jsonResponse[i].guid + '"><img height="22" src="assets/imgs/download.svg"></a>';
					if (jsonResponse[i].graph_uri == null) {
						bUnconverted = 1;
					} else {
						listDiv += '<a class="lstbtn" title="Bekijk deze dataset als graph" target="triply" href="https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/table?graph=' + jsonResponse[i].graph_uri + '"><img height="24" src="assets/imgs/cloud.svg"></a>';
					}
                } else {
					listDiv += ' &raquo; <span class="' + jsonResponse[i].state + '">'
                    listDiv += jsonResponse[i].state;
					listDiv += '</span>';
                    bUnconverted = 1;
                }
				

                listDiv += '</li>';
            }
            listDiv += '</ul>';
            bUpdateDatasetlist = bUnconverted;
            if (bUpdateDatasetlist == 1) {
                document.getElementById('datasetlist').classList.add("running");
                myTimer = setTimeout(updateDatasetlist, 4000);
            } else {
                document.getElementById('datasetlist').classList.remove("running");
            }
            document.getElementById('datasetlist').innerHTML = listDiv;
        }
    };
    req.send(null);
}

function deldataset(guid) {
    var element = document.getElementById("dataset_" + guid);
    element.parentNode.removeChild(element);
    var url = "delete.php?guid=" + guid;
    var req = new XMLHttpRequest();
    req.open('GET', url, true);
    req.send(null);
}

function MsgBox(msg, title) {
    var msgDiv = "";
    msgDiv += "<div id='msgboxDiv' class='msgBoxDivStyle'>";
    msgDiv += "<div id='msgboxContents' class='msgBoxContentsStyle'>";
    msgDiv += "<div id='msgboxTitle' class='msgBoxTitleStyle'>" + title + "</div>";
    msgDiv += "<div id='msgboxText' class='msgBoxTextStyle'>" + msg + "</div>";
    msgDiv += "<button id='answerOK' tabindex='1' class='btn msgBoxButtonStyle'onclick='document.body.removeChild(this.parentElement.parentElement);'>OK</button>";
    msgDiv += "</div></div>";
    document.body.insertAdjacentHTML("afterBegin", msgDiv);
    document.body.focus();

    document.getElementById('answerOK').onkeydown = function(e) {
        if (e.keyCode == 9) {
            return false;
        }
    }
    document.getElementById('answerOK').focus();
}