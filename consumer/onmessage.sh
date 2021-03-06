#!/bin/bash

RANDOM=$$
IFS='|'
read -ra ARR
guid=${ARR[0]}
orgid=${ARR[1]}
graphname=${ARR[2]}

# 5 minuten
MAXTRIES=300

TRIPLY_TOKEN=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_token FROM organisations WHERE id ='$orgid'";`
TRIPLY_USER=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_user FROM organisations WHERE id ='$orgid'";`
TRIPLY_DATASET=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT triply_dataset FROM organisations WHERE id ='$orgid'";`

NAMESPACE=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT namespace FROM organisations WHERE id ='$orgid'";`
NAMESPACEID=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT namespaceid FROM organisations WHERE id ='$orgid'";`
NAMESPACEDEF=`mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD  -s -N -e "SELECT namespacedef FROM organisations WHERE id ='$orgid'";`

API="https://data.netwerkdigitaalerfgoed.nl/_api"

JSON="/filestore/"$RANDOM".json"

echo "Starting with guid=$guid | graphname=$graphname | orgid=$orgid"

if [ "$graphname" = "kladblok" ]; then

	echo "Storing kladblok"

	URL="http://mi2rdf.netwerkdigitaalerfgoed.nl/kladblok/download.php?guid=$guid"

	curl -s --request POST -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs" --data-binary '{"url":"'$URL'","type":"download"}' > $JSON
	JOBID=`grep -o -E "\"jobId\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
	STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`


	while [[ ( "$STATUS" = "downloading" || "$STATUS" = "cleaning"  || "$STATUS" = "indexing" || "$STATUS" = "created" ) && $MAXTRIES>0 ]]; do
			curl -s -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs/$JOBID" > $JSON
			STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
			echo "STATUS: $STATUS (MAXTRIES: $MAXTRIES)"
			sleep 1
			MAXTRIES=$((MAXTRIES-1))
	done

	if [ "$STATUS" == "error" ]; then
		mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='error' WHERE guid='$guid'"
	fi

	if [ "$STATUS" == "finished" ]; then
			graph=`grep -o -E https://data.netwerkdigitaalerfgoed.nl/$TRIPLY_USER/$TRIPLY_DATASET/graphs/[a-z0-9\-]+ $JSON`

			curl -s --request GET -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs" > $JSON

			graphId=`grep -Pzo '"graphName": "'$graph'",\n\s*"id": "(.*?)"' $JSON | tail -1 | sed 's/\s*"id": "//' | sed 's/"//' | tr '\0' '\n'`
			kladblokId=`grep -Pzo '"graphName": ".*?kladblok",\n\s*"id": "(.*?)"' $JSON | tail -1 | sed 's/\s*"id": "//' | sed 's/"//' | tr '\0' '\n'`
			if [ ! -z "$kladblokId" ]; then
				echo "Remove kladblok graph with id $kladblokId"
				curl -s -X DELETE -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$kladblokId"
			fi

			echo "Renaming graph $graphId from $graph to $graphname"
			curl -s -X PATCH -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN"  --data-binary '{"graphName":"https://data.netwerkdigitaalerfgoed.nl/'$TRIPLY_USER'/'$TRIPLY_DATASET'/graphs/'$graphname'"}' "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$graphId" > $JSON
			
			rm $JSON
	fi
	
else

	# Do conversion magic 

	mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converting' WHERE guid='$guid'"

	cd /MFXML-to-JSONLD
	echo "MFXML-to-JSONLD"
	python3 mf2jsonld.py --xml /filestore/$orgid/$guid.txt --adt_id $orgid --uribase $NAMESPACE --skipfields /filestore/$orgid/skipfields.csv  > /filestore/$orgid/$guid.json  2> /filestore/$orgid/$guid.json.err

	node --max-old-space-size=8192 /usr/local/bin/jsonld normalize /filestore/$orgid/$guid.json > /filestore/$orgid/$guid.nq 2> /filestore/$orgid/guid.ttl.err

	rapper -i nquads \
	  -f 'xmlns:def="'$NAMESPACEDEF'"' \
	  -f 'xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"' \
	  -f 'xmlns:wd="http://www.wikidata.org/entity/"' \
	  -f 'xmlns:id="'$NAMESPACEID'"' \
	  -f 'xmlns:dct="http://purl.org/dc/terms/"' \
	  -f 'xmlns:aet="'$NAMESPACEDEF'aet#"' \
	  -f 'xmlns:rico="https://www.ica.org/standards/RiC/ontology#"' \
	  -o turtle /filestore/$orgid/$guid.nq > /filestore/$orgid/$guid.ttl  2>> /filestore/$orgid/$guid.ttl.err
	  
	echo "Created ttl/$BASE.ttl" \

	#TODO
	#|| head "nq/$BASE.nq" # in case of error


	if [ -e "/filestore/$orgid/$guid.ttl" ]; then
		mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='converted',converted=NOW() WHERE guid='$guid'"

		echo "$guid converted"

		if [ "" != "$TRIPLY_TOKEN" ]; then

			URL="http://mi2rdf.netwerkdigitaalerfgoed.nl/download.php?guid=$guid&org=$orgid"

			curl -s --request POST -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/jobs" --data-binary '{"url":"'$URL'","type":"download"}' > $JSON

			JOBID=`grep -o -E "\"jobId\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`
			STATUS=`grep -o -E "\"status\":\s+\".*\"" $JSON | awk -F\" '{print $4}'`

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

				curl -s --request GET -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN" "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs" > $JSON
				cat $JSON
				graphId=`grep -Pzo '"graphName": "'$graph'",\n\s*"id": "(.*?)"' $JSON | tail -1 | sed 's/\s*"id": "//' | sed 's/"//' | tr '\0' '\n'`

				graphname+="-"$RANDOM

				echo "Renaming graph $graphId from $graph to $graphname"
				curl -s -X PATCH -H 'Content-Type: application/json' -H "Authorization: Bearer $TRIPLY_TOKEN"  --data-binary '{"graphName":"https://data.netwerkdigitaalerfgoed.nl/'$TRIPLY_USER'/'$TRIPLY_DATASET'/graphs/'$graphname'"}' "$API/datasets/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$graphId" > $JSON
				mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET graph_uri='https://data.netwerkdigitaalerfgoed.nl/$TRIPLY_USER/$TRIPLY_DATASET/graphs/$graphname' WHERE guid='$guid'"
				rm $JSON
			fi
		else
			mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET graph_uri='' WHERE guid='$guid'"
			echo "TRIPLY_TOKEN environment variable not found, so no upload to TRIPLY"
		fi
	else
		mysql mi2rdf -h mi2rdf-database -u $MYSQL_USER --password=$MYSQL_PASSWORD -e "UPDATE datasets SET state='error',converted=NOW() WHERE guid='$guid'"
		echo "ERROR: file $guid was not converted to .ttl"
	fi

fi