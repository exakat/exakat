<?php

rd_kafka_errno2err();
rd_kafka_not_function( );


$rk = new RdKafka\Producer();
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers("127.0.0.1");

$topic = $rk->newTopic("test");

for ($i = 0; $i < 10; $i++) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message $i");
    $rk->poll(0);
}

while ($rk->getOutQLen() > 0) {
    $rk->poll(50);
}


?>