<?php

$expected     = array('if($a) { /**/ } elseif($a || $b || $c || $d) { /**/ } ',
                      'if($b) { /**/ } elseif($a || $b || $c || $d) { /**/ } ',
                      'if($c) { /**/ } elseif($a || $b || $c || $d) { /**/ } ',
                      'if($d) { /**/ } elseif($a || $b || $c || $d) { /**/ } ',
                     );

$expected_not = array('if($e) { /**/ } elseif($a || $b || $c || $d) { /**/ } ',
                      '',
                     );

?>