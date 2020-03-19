/* drag-and-drop file */

let dropArea = document.getElementById('drop-area')


// Prevent default drag behaviors
;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
	dropArea.addEventListener(eventName, preventDefaults, false)   
	document.body.addEventListener(eventName, preventDefaults, false)
})

// Highlight drop area when item is dragged over it
;['dragenter', 'dragover'].forEach(eventName => {
	dropArea.addEventListener(eventName, highlight, false)
})

;['dragleave', 'drop'].forEach(eventName => {
	dropArea.addEventListener(eventName, unhighlight, false)
})

// Handle dropped files
dropArea.addEventListener('drop', handleDrop, false)

function preventDefaults (e) {
	e.preventDefault()
	e.stopPropagation()
}

function highlight(e) {
	dropArea.classList.add('highlight')
}

function unhighlight(e) {
	dropArea.classList.remove('highlight')
}

function handleDrop(e) {
        e.preventDefault();
        var files = e.dataTransfer.files
        for (var i = 0; i < files.length; i++) {
                uploadFile(files[i]);
        }
}

var myTimer;
var bUpdateDatasetlist=0;

function uploadFile(file) {
	var filelength = parseInt(file.name.length) - 3;
	var fileext = file.name.substring(filelength,filelength + 3);
	if (fileext.toLowerCase() != "txt" && fileext.toLowerCase() != "zip"){
		MsgBox("U kunt alleen een MAIS internet bestand uploaden (herkenbaar aan de bestandsextensie .txt) of een of meer gecomprimeerde MAIS internet bestanden in één ZIP bestand.", "Foutmelding");
		return;
	}
	
	var url = 'upload.php';
	var xhr = new XMLHttpRequest();
	var formData = new FormData();
	xhr.open('POST', url, true);
	xhr.addEventListener('readystatechange', function(e) {
		if (xhr.readyState == 4 && xhr.status == 200) {
			bUpdateDatasetlist=1;
			updateDatasetlist();
		} else if (xhr.readyState == 4 && xhr.status != 200) {
			// Error. Inform the user
		}
	})
	formData.append('file', file);
	xhr.send(formData);
}

function updateDatasetlist() {
	
	clearTimeout(myTimer);
	var url = 'datasets.php'
	var req = new XMLHttpRequest();
	req.responseType = 'json';
	req.open('GET', url, true);
	req.onload  = function() {
		if (req.response) {
			var jsonResponse = req.response;
			var listDiv="<ul class='dlist'>";
			var bUnconverted=0;
			for(var i = 0; i < jsonResponse.length; i++) {
				listDiv+='<li title="Originele bestandsnaam: '+jsonResponse[i].org_name+' | Aangeleverd: '+jsonResponse[i].created+' | Omgezet: '+jsonResponse[i].converted+'">';
				listDiv+=jsonResponse[i].id+' - '+jsonResponse[i].org_name;
				listDiv+=' &raquo; <span class="'+jsonResponse[i].state+'">'
				if (jsonResponse[i].state=='converted') {
					listDiv+='<a href="download.php?guid='+jsonResponse[i].guid+'">';
					listDiv+=jsonResponse[i].state;
					listDiv+='</a>';
				} else {
					listDiv+=jsonResponse[i].state;
					bUnconverted=1;
				}
				listDiv+='</span></li>';
			}
			listDiv+='</ul>';
			bUpdateDatasetlist=bUnconverted;
			document.getElementById('datasetlist').innerHTML=listDiv;

			if (bUpdateDatasetlist==1) {
				myTimer=setTimeout(updateDatasetlist, 4000);
			}
		}
	};
	req.send(null);
}
updateDatasetlist();

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

	document.getElementById('answerOK').onkeydown = function(e){if(e.keyCode==9){return false;}}
	document.getElementById('answerOK').focus();

}