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
		if (fileext.toLowerCase() != "txt" && fileext.toLowerCase() != "xml" && fileext.toLowerCase() != "zip") {
			MsgBox("U kunt alleen een MAIS export bestand (.xml) of een of meer gecomprimeerde bestanden in één ZIP bestand uploaden.", "Foutmelding");
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

		$('[data-toggle="tooltip"]').tooltip('dispose');
		
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
					listDiv += '<li id="dataset_' + jsonResponse[i].guid + '">';
					listDiv += '<span class="nrid">'+jsonResponse[i].id + '</span> ';
					listDiv += '<span data-html="true" data-toggle="tooltip" data-placement="top" title="<b>Originele bestandsnaam</b>:<br>' + jsonResponse[i].org_name + '<br><b>Aangeleverd</b>:<br>' + jsonResponse[i].created + '<br><b>Omgezet</b>:<br>' + jsonResponse[i].converted + '">';
					if (jsonResponse[i].org_name.length<namecutoff) {
						listDiv += jsonResponse[i].org_name;
					} else {
						listDiv += jsonResponse[i].org_name.substr(0,namecutoff-3)+'&hellip;';
					}
					listDiv += '</span>';
					listDiv += '<a class="lstbtn" data-toggle="tooltip" data-placement="top" title="Verwijder deze dataset';
					if (triply_user!="") { listDiv += ' (er wordt geen graph in Triply verwijderd)'; }
					listDiv += '" href="#" onclick="deldataset(\'' + jsonResponse[i].guid + '\')"><img height="16" src="assets/imgs/trash.svg"></a>';
					
					listDiv += '<a class="lstbtn logmodal" data-toggle="tooltip" data-placement="top" title="Bekijk de logging" href="logging.php?guid=' + jsonResponse[i].guid + '"><img height="16" src="assets/imgs/logging.svg"></a>';
					
					if (jsonResponse[i].state == 'converted') {
						listDiv += '<a class="lstbtn downloadmodal" data-toggle="tooltip" data-guid="' + jsonResponse[i].guid + '" data-placement="top" title="Download deze dataset" href="#"><img height="22" src="assets/imgs/download.svg"></a>';
						if (jsonResponse[i].graph_uri == null) {
							bUnconverted = 1;
							listDiv += '<br>&nbsp;&raquo; <span class="converting">To Triply</span>';
						} else {
							if (jsonResponse[i].graph_uri != "") {
								listDiv += '<a class="lstbtn" data-toggle="tooltip" data-placement="top" title="Bekijk deze dataset als graph" target="triply" href="https://data.netwerkdigitaalerfgoed.nl/'+triply_user+'/'+triply_dataset+'/table?graph=' + jsonResponse[i].graph_uri + '"><img height="24" src="assets/imgs/cloud.svg"></a>';
							} else {
								listDiv += '<span class="lstbtn" style="opacity:0.4" data-toggle="tooltip" data-placement="top" title="Er is geen (link naar een) graph beschikbaar."><img height="24" src="assets/imgs/cloud.svg"></span>';
							}
						}
					} else {
						listDiv += '<br>&nbsp;&raquo; <span class="' + jsonResponse[i].state + '">'
						listDiv += jsonResponse[i].state[0].toUpperCase() + jsonResponse[i].state.slice(1);
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
				
				$('.logmodal').on('click', function(e){
				  e.preventDefault()
				  $('#logModalBody').html("<p style='text-align:center'>Logfile wordt geladen ...</p>");
				  $('#logModal').modal('show');
				  $('#logModalBody').load($(this).attr('href'));
				});
	
				$('.downloadmodal').on('click', function(e){
				  var guid = $(this).attr('data-guid');
				  $('#dsrc').attr('href','download.php?guid='+guid+'&type=src');
				  $('#djson').attr('href','download.php?guid='+guid+'&type=json');
				  $('#dttl').attr('href','download.php?guid='+guid+'&type=ttl');
				  $('#downloadModal').modal('show');
				});
				
				$('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
			}
		};
		req.send(null);
	}

	function deldataset(guid) {
		$('[data-toggle="tooltip"]').tooltip('dispose');
		
		var element = document.getElementById("dataset_" + guid);
		element.parentNode.removeChild(element);
		var url = "delete.php?guid=" + guid;
		var req = new XMLHttpRequest();
		req.open('GET', url, true);
		req.send(null);
		
		$('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
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
