#!/bin/bash

RANDOM=$$
IFS='|'
read -ra ARR
guid=${ARR[0]}
graphname=${ARR[1]}"-"$RANDOM

echo "Starting with guid=$guid and graphname=$graphname"
mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converting' WHERE guid='$guid'"

# Do conversion magic 

if [ -e /filestore/$guid.txt ]; do
	cd /MDWS-to-JSON
	echo "MDWS-to-JSON"
	node ./index.js /filestore/$guid.txt > /filestore/$guid.json 2> /filestore/$guid.json.err
else
	if [ -e /filestore/$guid.xml ]; do
		cd /MF-Export-XML-to-JSON
		echo "MF-Export-XML-to-JSON"
		node ./index.js /filestore/$guid.xml > /filestore/$guid.json 2> /filestore/$guid.json.err
	else
		echo "/filestore/$guid.txt or /filestore/$guid.xml could not be found" > /filestore/$guid.json.err
		exit;
	fi
fi

echo "MDWS-JSON-to-Turtle"
cd /MDWS-JSON-to-Turtle
node ./index.js /filestore/$guid.json > /filestore/$guid.ttl 2> /filestore/$guid.ttl.err

if [ -e "/filestore/$guid.ttl" ]; then
	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converted',converted=NOW() WHERE guid='$guid'"

	echo "$guid converted"

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