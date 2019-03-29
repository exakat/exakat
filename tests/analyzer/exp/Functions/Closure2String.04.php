<?php

$expected     = array('function ($o2) use ($a) { /**/ } ',
                      'function ($O2) { /**/ } ',
                     );

$expected_not = array('function ($o1) { /**/ } ',
                      'function ($o3) { /**/ } ',
                      'function ($O1) { /**/ } ',
                     );

?>