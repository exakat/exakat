<?php

$expected     = array('class TestAtoum2 extends \\atoum\\TEST { /**/ } ',
                      'class TestSimpleTest extends UnitTestCase { /**/ } ',
                      'class TestPHPUnit extends PHPUnit_Framework_Assert { /**/ } ',
                      'class TestAtoum extends atoum\\TEST { /**/ } ',
                     );

$expected_not = array('class mySimpleTest extends myOwnTest { /**/ } ',
                     );

?>