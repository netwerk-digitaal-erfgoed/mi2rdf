<?php

define("UPLOAD_DIR","/filestore/");

define("DB_HOST","mi2rdf-database");
define("DB_USER",getenv('MYSQL_USER'));
define("DB_PASS",getenv('MYSQL_PASSWORD'));
define("DB_DATA",getenv('MYSQL_DATABASE'));

define('RABBIT_HOST', 'mi2rdf-queue');
define('RABBIT_PORT', 5672);
define('RABBIT_USER', 'mi2rdf'); # getenv('RABBITMQ_DEFAULT_USER')
define('RABBIT_PASS', 'mi2rdf'); # getenv('RABBITMQ_DEFAULT_PASS')

define("RABBIT_QUEUE_NAME", "mi2rdf");

define("MAX_LIST", 200);

session_start();