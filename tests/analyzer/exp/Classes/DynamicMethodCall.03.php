<?php

$expected     = array('$object->{$method}(1)',
                      '$class::{$method}(1)',
                     );

$expected_not = array('$object->method( )',
                      '$class::methode( )',
                     );

?>