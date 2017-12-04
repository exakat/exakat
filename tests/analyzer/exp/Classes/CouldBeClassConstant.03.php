<?php

$expected     = array('static $nonEmptyArray = array(1)',
                      'static $emptyArray = array( )',
                      'static $string = \'Indeed a string\'',
                      'static $boolean = true',
                      'static $integer = 1',
                     );

$expected_not = array('static $string = \'Indeed a string\'',
                     );

?>