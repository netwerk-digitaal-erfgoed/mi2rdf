#!/bin/bash

read guid

mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converting' WHERE guid='$guid'"

# Do conversion magic 
node /MDWS-to-JSON/index.js /filestore/$guid.txt > /filestore/$guid.json
node /MDWS-JSON-to-Turtle/index.js /filestore/$guid.json > /filestore/$guid.ttl

if [ -e "/filestore/$guid.ttl" ]; then
	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converted',converted=NOW() WHERE guid='$guid'"

	echo "$guid converted"

	if [ "" != "$TRIPLY_TOKEN" ]; then
		RANDOM=$$
		JSON=$RANDOM".json"

		URL="http://demo.netwerkdigitaalerfgoed.nl/mi2rdf/download.php?guid=$guid"
		API="https://data.netwerkdigitaalerfgoed.nl/_api"

		echo "curl POST $API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs $URL obv token $TRIPLY_TOKEN"	
		curl -s --request POST -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs" --data-binary '{"url":"'$URL'","type":"download"}' > $JSON

		JOBID=`grep -o -E "\"jobId\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
		STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`

		MAXTRIES=30

		while [[ ( "$STATUS" = "downloading" || "$STATUS" = "cleaning"  || "$STATUS" = "indexing" || "$STATUS" = "created" ) && $MAXTRIES>0 ]]; do
			echo "MAXTRIES: $MAXTRIES"	
			curl -s -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs/$JOBID" > $JSON
			STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
			echo "STATUS: $STATUS"
			sleep 1
			MAXTRIES=$((MAXTRIES-1))
		done

		if [ "$STATUS" == "finished" ]; then
			graph=`grep -o -E https://data.netwerkdigitaalerfgoed.nl/$TRIPLY_USER/$TRIPLY_DATASET/graphs/[a-z0-9]+ $JSON`
			mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET graph_uri='$graph' WHERE guid='$guid'"
			echo "GRAPH: $graph"
			rm JSON
		fi
	else
		echo "TRIPLY_TOKEN environment variable not found, so no upload to TRIPLY"
	fi
else
	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='error',converted=NOW() WHERE guid='$guid'"
	echo "ERROR: file $guid was not converted to .ttl"
fi