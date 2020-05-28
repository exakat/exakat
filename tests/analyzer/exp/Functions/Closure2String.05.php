<?php

$expected     = array('function ($o1) { /**/ } ',
                      'function ($o5) { /**/ } ',
                      'function ($o3) { /**/ } ',
                      'function ($o2) use ($a) { /**/ } ',
                      'function ($o4) { /**/ } ',
                     );

$expected_not = array('function ($o6) { /**/ } ',
                      'function ($o7) { /**/ } ',
                     );

?>