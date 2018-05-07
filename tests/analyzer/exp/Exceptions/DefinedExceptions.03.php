<?php

$expected     = array('class XYY extends XY { /**/ } ',
                      'class XYYY extends XYY { /**/ } ',
                      'class XY extends X { /**/ } ',
                      'class XZ extends X { /**/ } ',
                      'class X extends \\Exception { /**/ } ',
                     );

$expected_not = array('class XYYY5 extends XYY5 { /**/ } ',
                     );

?>