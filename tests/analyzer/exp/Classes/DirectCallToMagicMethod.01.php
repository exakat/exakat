<?php

$expected     = array('stclass::__sleep($a)',
                      '$a->__get($a)',
                      'stclass::__clone($a)',
                     );

$expected_not = array('__get($b)',
                      '$b->__set2($a)',
                     );

?>