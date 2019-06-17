<?php

$expected     = array('class normalClass { /**/ } ',
                      'class TestB3 extends TestB2 { /**/ } ',
                     );

$expected_not = array('class TestAtoum extends PHPUnit { /**/ } ',
                      'class TestAtoum2 extends TestAtoum { /**/ } ',
                      'class TestAtoum3 extends TestAtoum2 { /**/ } ',
                      'class TestB extends TEST { /**/ } ',
                      'class TestB2 extends TestB { /**/ } ',
                     );

?>