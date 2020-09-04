<?php

$expected     = array('if($b) { /**/ } elseif($a || $b) { /**/ } ',
                      'if($a || $b) { /**/ } elseif($a) { /**/ } ',
                      'if($a) { /**/ } elseif($a || $b) { /**/ } ',
                      'if($b || $a) { /**/ } elseif($a || $b) { /**/ } ',
                      'if($a OR $b) { /**/ } elseif($b) { /**/ } ',
                      'if($a or $b) { /**/ } elseif($b || $a) { /**/ } ',
                     );

$expected_not = array('if ($a) { /**/ } elseif ($b) { /**/ } ',
                      'if ($a && $b) { /**/ } elseif ($a) { /**/ } ',
                     );

?>