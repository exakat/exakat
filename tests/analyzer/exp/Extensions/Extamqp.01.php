<?php

$expected     = array('AMQPConnection( )',
                      'AMQPChannel($cnn)',
                      'AMQPChannel($cnn)',
                     );

$expected_not = array('\\X\\AMQPChannel(null)',
                     );

?>