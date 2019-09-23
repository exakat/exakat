<?php

$expected     = array('function ($o1) { /**/ } ',
                      'function ($O1) { /**/ } ',
                     );

$expected_not = array('function ($o3) { /**/ } ',
                      'function ($o2) use ($a) { /**/ } ',
                      'function ($O2) { /**/ } ',
                     );

?>