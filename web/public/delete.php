<?php

include('includes/config.php');
include('includes/database.php');

if (isset($_GET["guid"])) {
	$guid=preg_replace('/[^A-F0-9\-]/i','',$_GET["guid"]);
	if (strlen($guid)==36) {
		
		// bestanden
		$files = glob(UPLOAD_DIR.$guid.'*');
		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
			}
		}
		
		// graph		
		if (isset($_SESSION["organisation"]["triply_token"])) {
			
			$dataset=arrGetDataset($guid);

			if (isset($dataset) && isset($dataset["graph_uri"])) {
				$graphName=$dataset["graph_uri"]; 
				
				$ch = curl_init();
				$url="https://data.netwerkdigitaalerfgoed.nl/_api/datasets/".$_SESSION["organisation"]["triply_user"]."/".$_SESSION["organisation"]["triply_dataset"]."/graphs";
				error_log("DEBUG: $url");
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Authorization: Bearer '.$_SESSION["organisation"]["triply_token"]));
				$output = curl_exec($ch);
				curl_close($ch);     

				$graphs=json_decode($output,true);
				foreach ($graphs as $graph) {
					if ($graph["graphName"]==$graphName) {
						$id=$graph["id"];
					}
				}
				
				if (isset($id)) {
					$ch = curl_init();
					$url='https://data.netwerkdigitaalerfgoed.nl/_api/datasets/'.$_SESSION["organisation"]["triply_user"].'/'.$_SESSION["organisation"]["triply_dataset"].'/graphs/'.$id;
					error_log("DEBUG: $url");
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$_SESSION["organisation"]["triply_token"]));
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch);
					curl_close($ch);     
				} else {
					error_log("WARN: graph $graphName niet gevonden");
				}				
			} else {
				error_log("ERROR: geen graph_uri gevonden voor dataset $guid: ".print_r($dataset,1));
			}
		} else {
			error_log("ERROR: geen triply_token beschikbaar op sessie");
		}
		
		// database
		fDeleteDataset($guid);
		
	}
	
} else {
	error_log("ERROR: invalid guid");
}