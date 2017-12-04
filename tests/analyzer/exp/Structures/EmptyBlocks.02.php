<?php

$expected     = array('switch ($d) { /**/ } ',
                      'switch ($a) : /**/  endswitch',
                     );

$expected_not = array('switch ($b) { /**/ } ',
                      'switch ($c) : /**/  endswitch',
                     );

?>