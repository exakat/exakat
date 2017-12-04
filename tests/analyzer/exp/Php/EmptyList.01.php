<?php

$expected     = array('list( ,  ,  )',
                      'list( )',
                      'list( )',
                     );

$expected_not = array('list($x, list(), $y)',
                      'list($x, , $y)',
                     );

?>