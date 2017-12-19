<?php

$expected     = array('$mask || $mask',
                      '$login == $login',
                      '$object->login( ) !== $object->login( )',
                      '$sum >= $sum',
                      '$mask && $mask',
                     );

$expected_not = array('$login == $login2',
                     );

?>