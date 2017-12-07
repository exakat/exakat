<?php

$expected     = array('interface IT { /**/ } ',
                      'class CT { /**/ } ',
                      'trait CT { /**/ } ',
                      'interface CI { /**/ } ',
                      'class CI { /**/ } ',
                      'trait IT { /**/ } ',
                     );

$expected_not = array('interface Tu { /**/ } ',
                      'class Tv { /**/ } ',
                      'trait Tx { /**/ } ',
                     );

?>