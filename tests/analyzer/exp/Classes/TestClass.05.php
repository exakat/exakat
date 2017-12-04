<?php

$expected     = array('class TestAtoum extends TEST { /**/ } ',
                      'class TestPHPUnit extends PHPUnit { /**/ } ',
                      'class TestAtoum2 extends TestAtoum { /**/ } ',
                      'class TestAtoum3 extends TestAtoum2 { /**/ } ',
                     );

$expected_not = array('class TestAtoum2 extends \\TEST { /**/ } ',
                      'class TestSimpleTest extends UnitTestCase { /**/ } ',
                     );

?>