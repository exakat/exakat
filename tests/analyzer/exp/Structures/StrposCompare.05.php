<?php

$expected     = array('stripos($b, $c)',
                      'stripos($b2, $c2)',
                     );

$expected_not = array('stripos($b2, $c2)',
                      'stripos($b4, $c4)',
                     );

?>