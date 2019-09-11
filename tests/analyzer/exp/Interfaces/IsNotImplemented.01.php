<?php

$expected     = array('class ai1 implements i1 { /**/ } ',
                      'class ai5 extends ai4 { /**/ } ',
                     );

$expected_not = array('class ai2 implements i1 { /**/ } ',
                      'abstract class ai3 implements i1 { /**/ } ',
                      'abstract class ai4 implements i1 { /**/ } ',
                     );

?>