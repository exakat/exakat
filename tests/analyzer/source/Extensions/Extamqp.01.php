<?php
$cnn = new AMQPConnection();
$cnn->connect();
$ch = new AMQPChannel($cnn);
echo get_class($ch) . "\n";
echo $ch->isConnected() ? 'true' : 'false';
echo "\n";
$ch2 = new AMQPChannel($cnn);
echo get_class($ch) . "\n";
echo $ch->isConnected() ? 'true' : 'false';
unset($ch2);
$ch->setPrefetchCount(10);

$ch2 = new \X\AMQPChannel(null);

?>