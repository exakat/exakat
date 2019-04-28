<?php

$expected     = array('preg_replace(\'$(a)$sie\', \'b\', $c)',
                      'preg_replace(\'*(a)*sie\', \'b\', $c)',
                      'preg_replace(\'?(a)?sie\', \'b\', $c)',
                      'preg_replace(\'|(a)|sie\', \'b\', $c)',
                      'preg_replace(\'+(a)+sie\', \'b\', $c)',
                      'preg_replace(\'.(a).sie\', \'b\', $c)',
                     );

$expected_not = array('preg_replace(\'\\"(a)\\"sie\', \'b\', $c)',
                      'preg_replace(\'\\"(a)\\"si\', \'b\', $c)',
                     );

?>