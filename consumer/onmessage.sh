#!/bin/bash

read guid

mysql mi2rdf -h mi2rdf-database -u mi2rdf --password=mi2rdf -e "UPDATE datasets SET state='converting' WHERE guid='$guid'"

# Do conversion magic 
node /MDWS-to-JSON/index.js /var/www/html/filestore/$guid.txt > /var/www/html/filestore/$guid.json
node /MDWS-JSON-to-Turtle/index.js /var/www/html/filestore/$guid.json > /var/www/html/filestore/$guid.ttl

mysql mi2rdf -h mi2rdf-database -u mi2rdf --password=mi2rdf -e "UPDATE datasets SET state='converted',converted=NOW() WHERE guid='$guid'"

echo "$guid converted"