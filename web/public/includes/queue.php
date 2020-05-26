<?php

require_once '/var/www/html/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

function fAddToQueue($guid,$filename,$organisation_id) {	
	error_log("INFO: fAddToQueue($guid,$filename)");
	
	$content=$guid."|".$organisation_id."|".preg_replace('/[^a-z0-9_\-]/i','',preg_replace('/\.txt$/i','',$filename));
	$connection = new AMQPConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);
	$channel = $connection->channel();
	$channel->queue_declare(RABBIT_QUEUE_NAME, true, false, false, false);
	$msg = new AMQPMessage($content, array('delivery_mode' => 2)); # make message persistent
	$channel->basic_publish($msg, '', RABBIT_QUEUE_NAME);
	$channel->close();
	$connection->close();
}


function do_task($queue,$callback,$version=1) {
	$connection = new AMQPConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);
	$channel = $connection->channel();
	$channel->queue_declare($queue, false, true, false, false);
	$channel->basic_qos(null, 1, null);
	$channel->basic_consume($queue, '', false, false, false, false, $callback);
	log_task("[i] $queue (v$version) worker started");
	
	$max=250000;
	
	while($max-->0 && count($channel->callbacks)) {
		try {
			$channel->wait();
		} catch (\Exception $e) {
			$connection->reconnect();
			$channel->consume();
		}
	}
	
	log_task("[i] $queue worker going away");
	
	$channel->close();
	$connection->close();
}

function log_task($msg) {
	echo date(DATE_ATOM)." > ".$msg."\n";
}

