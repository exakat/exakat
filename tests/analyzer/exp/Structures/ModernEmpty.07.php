<?php

$expected     = array('$this->a = strtolower($b . $c)',
                     );

$expected_not = array('strtolower($b . $c)',
                      'strtolower($b0 . $c0)',
                      '$this->a2 = strtolower($b . $c)',
                      '$this->d = strtolower($b0 . $c0)',
                      '$this->a2 = strtolower($b . $c)',
                     );

?>