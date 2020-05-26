#!/bin/bash

RANDOM=$$
IFS='|'
read -ra ARR
guid=${ARR[0]}
orgid=${ARR[1]}
graphname=${ARR[2]}"-"$RANDOM

echo "Starting with guid=$guid | graphname=$graphname | orgid=$orgid"
mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converting' WHERE guid='$guid'"

# Do conversion magic 

if [ -e "/filestore/$guid.txt" ]; then
	firstchar=$(head -c 1 /filestore/$guid.txt)
	if [ "$firstchar" = "%" ]; then
		cd /MDWS-to-JSON
		echo "MDWS-to-JSON"
		node ./index.js /filestore/$guid.txt > /filestore/$guid.json 2> /filestore/$guid.json.err
	else 
		# XML bestand vermomd als een .txt bestand
		if [ "$firstchar" = "<" ]; then
			cd /MF-Export-XML-to-JSON
			echo "MF-Export-XML-to-JSON"
			node ./index.js /filestore/$guid.txt > /filestore/$guid.json 2> /filestore/$guid.json.err
		else
			echo "/filestore/$guid.txt onverwachte inhoud (eerste karakter=$firstchar)"  > /filestore/$guid.json.err
			exit;
		fi
	fi
else
	if [ -e "/filestore/$guid.xml" ]; then
		cd /MF-Export-XML-to-JSON
		echo "MF-Export-XML-to-JSON"
		node ./index.js /filestore/$guid.xml > /filestore/$guid.json 2> /filestore/$guid.json.err
	else
		echo "/filestore/$guid.txt or /filestore/$guid.xml could not be found" > /filestore/$guid.json.err
		exit;
	fi
fi

NAMESPACE=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT namespace FROM organisations WHERE id ='$orgid'";`

echo "MDWS-JSON-to-Turtle (with namespace $NAMESPACE)"
cd /MDWS-JSON-to-Turtle
node ./index.js /filestore/$guid.json $NAMESPACE > /filestore/$guid.ttl 2> /filestore/$guid.ttl.err

if [ -e "/filestore/$guid.ttl" ]; then
	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converted',converted=NOW() WHERE guid='$guid'"

	echo "$guid converted"

	TRIPLY_TOKEN=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_token FROM organisations WHERE id ='$orgid'";`
	TRIPLY_USER=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_user FROM organisations WHERE id ='$orgid'";`
	TRIPLY_DATASET=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_dataset FROM organisations WHERE id ='$orgid'";`


	if [ "" != "$TRIPLY_TOKEN" ]; then
		JSON="/filestore/"$RANDOM".json"

		URL="http://demo.netwerkdigitaalerfgoed.nl/mi2rdf/download.php?guid=$guid"
		API="https://data.netwerkdigitaalerfgoed.nl/_api"

		echo "curl POST $API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs $URL obv token $TRIPLY_TOKEN"	
		curl -s --request POST -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs" --data-binary '{"url":"'$URL'","type":"download"}' > $JSON

		JOBID=`grep -o -E "\"jobId\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
		STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`

		# 5 minuten
		MAXTRIES=300

		while [[ ( "$STATUS" = "downloading" || "$STATUS" = "cleaning"  || "$STATUS" = "indexing" || "$STATUS" = "created" ) && $MAXTRIES>0 ]]; do
			echo "MAXTRIES: $MAXTRIES"	
			curl -s -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs/$JOBID" > $JSON
			STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
			echo "STATUS: $STATUS"
			sleep 2
			MAXTRIES=$((MAXTRIES-1))
		done

		if [ "$STATUS" == "finished" ]; then
			graph=`grep -o -E https://data.netwerkdigitaalerfgoed.nl/$TRIPLY_USER/$TRIPLY_DATASET/graphs/[a-z0-9\-]+ $JSON`
			echo "curl -s \"$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs\" | grep -Pzo '\"graphName\": \"'$graph'\",\n\s*\"id\": \"(.*?)\"' | tail -1 | sed 's/\s*\"id\": \"//' | sed 's/\"//'"
			graphId=`curl -s "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs" | grep -Pzo '"graphName": "'$graph'",\n\s*"id": "(.*?)"' | tail -1 | sed 's/\s*"id": "//' | sed 's/"//'`

			echo "Renaming graph $graphId from $graph to $graphname"
			curl -s -X PATCH -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN"  --data-binary '{"graphName":"https://data.netwerkdigitaalerfgoed.nl/'$TRIPLY_USER'/'$TRIPLY_DATASET'/graphs/'$graphname'"}' "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$graphId" > $JSON
			mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET graph_uri='https://data.netwerkdigitaalerfgoed.nl/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$graphname' WHERE guid='$guid'"
			#rm $JSON
		fi
	else
		mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET graph_uri='' WHERE guid='$guid'"
		echo "TRIPLY_TOKEN environment variable not found, so no upload to TRIPLY"
	fi
else
	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='error',converted=NOW() WHERE guid='$guid'"
	echo "ERROR: file $guid was not converted to .ttl"
fi