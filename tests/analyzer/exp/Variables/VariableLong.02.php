<?php

$expected     = array('$abcdefghijklmnopqrstuvwxyz',
                      '$array__jklmnopqrstuvwxyz',
                     );

$expected_not = array('$static__jklmnopqrstuvwxyz',
                      '$global__jklmnopqrstuvwxyz',
                      '$propertyjklmnopqrstuvwxyz',
                     );

?>