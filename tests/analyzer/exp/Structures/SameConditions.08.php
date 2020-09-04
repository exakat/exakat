<?php

$expected     = array('if($a || $b || $c || $d) { /**/ } elseif($a) { /**/ } ',
                      'if($a || $b || $c || $d) { /**/ } elseif($b) { /**/ } ',
                      'if($a || $b || $c || $d) { /**/ } elseif($c) { /**/ } ',
                      'if($a || $b || $c || $d) { /**/ } elseif($d) { /**/ } ',
                     );

$expected_not = array('if($a || $b || $c || $d) { /**/ } elseif($e) { /**/ } ',
                      '',
                     );

?>