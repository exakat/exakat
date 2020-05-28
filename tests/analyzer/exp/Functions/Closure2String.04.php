<?php

$expected     = array('function ($o1) { /**/ } ',
                      'function ($o3) { /**/ } ',
                      'function ($O1) { /**/ } ',
                      'function ($O2) { /**/ } ',
                      'function ($o2) use ($a) { /**/ } ',
                      'function ($o2a) { /**/ } ',
                     );

$expected_not = array('function ($o2b) { /**/ } ',
                     );

?>