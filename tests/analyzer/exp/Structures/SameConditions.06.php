<?php

$expected     = array('if($a) { /**/ } elseif(($a) || $b) { /**/ } ',
                      'if($b) { /**/ } elseif($a || ($b)) { /**/ } ',
                      'if(($a) || $b) { /**/ } elseif($a) { /**/ } ',
                      'if($a OR ($b)) { /**/ } elseif($b) { /**/ } ',
                      'if($c = $a or ($b)) { /**/ } elseif($b || $a) { /**/ } ',
                      'if($b || $a) { /**/ } elseif(($c = $a) || ($c = $b)) { /**/ } ',
                     );

$expected_not = array('if ($a) { /**/ } elseif ($b) { /**/ } ',
                      'if ($a && $b) { /**/ } elseif ($a) { /**/ } ',
                     );

?>