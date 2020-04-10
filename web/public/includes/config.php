<?php

define("UPLOAD_DIR","/filestore/");

define("DB_HOST",getenv('MYSQL_DATABASE'));
define("DB_USER",getenv('MYSQL_USER'));
define("DB_PASS",getenv('MYSQL_PASSWORD'));
define("DB_DATA","mi2rdf");

define('RABBIT_HOST', 'mi2rdf-queue');
define('RABBIT_PORT', 5672);
define('RABBIT_USER', getenv('RABBITMQ_DEFAULT_USER'));
define('RABBIT_PASS', getenv('RABBITMQ_DEFAULT_PASS'));

define("RABBIT_QUEUE_NAME", "mi2rdf");