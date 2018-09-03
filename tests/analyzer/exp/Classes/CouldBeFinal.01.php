<?php

$expected     = array('class z extends y { /**/ } ',
                     );

$expected_not = array('final class z2 extends y { /**/ } ',
                      'class y extends x { /**/ } ',
                      'class x { /**/ } ',
                     );

?>