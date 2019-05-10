<?php

$expected     = array('while (self::$z > 10) { /**/ } ',
                      'while ($this->id > 10) { /**/ } ',
                      'while ($this->y > 10) { /**/ } ',
                     );

$expected_not = array('while (self::$z2 > 10) { /**/ } ',
                      'while ($this->id2 > 10) { /**/ } ',
                      'while ($this->y2 > 10) { /**/ } ',
                     );

?>