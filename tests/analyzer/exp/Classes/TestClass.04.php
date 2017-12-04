<?php

$expected     = array('class TestAtoum extends TEST { /**/ } ',
                      'class TestPHPUnit extends PHPUnit { /**/ } ',
                     );

$expected_not = array('class TestAtoum2 extends \\TEST { /**/ } ',
                      'class TestSimpleTest extends UnitTestCase { /**/ } ',
                     );

?>