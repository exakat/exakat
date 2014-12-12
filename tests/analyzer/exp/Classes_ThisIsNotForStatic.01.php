<?php

$expected     = array('function nonStaticMethod ( ) { /**/ } ',
                      'function nonStaticMethodInTrait ( ) { /**/ } ',
);

$expected_not = array('function staticMethod ( ) { /**/ } ',
                      'function staticMethodInTrait ( ) { /**/ } ',
                      'function realFunctionNoThis ( ) { /**/ } ',
                      'function realFunction ( ) { /**/ } ',
                      );

?>