#!/bin/bash

chown www-data:www-data /filestore

while :
do
  echo "INFO: Trying to start amqp-consume (is queue/rabbitmq available?)"
  amqp-consume -A -u amqp://mi2rdf:mi2rdf@mi2rdf-queue/%2f -q mi2rdf /bin/bash /onmessage.sh
  sleep 10
done