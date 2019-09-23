<?php

$expected     = array('function ($o1) { /**/ } ',
                      'function ($o5) { /**/ } ',
                     );

$expected_not = array('function ($o3) { /**/ } ',
                      'function ($o2) use ($a) { /**/ } ',
                      'function ($o4) { /**/ } ',
                     );

?>