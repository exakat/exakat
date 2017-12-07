<?php

$expected     = array('class conditionnedX { /**/ } ',
                      'interface conditionnedInterface { /**/ } ',
                      'trait conditionnedTrait { /**/ } ',
                      'function conditionnedFunction( ) { /**/ } ',
                      '\'ConditionedConstant\'',
                     );

$expected_not = array('class NormalX { /**/ } ',
                      'interface NormalInterface { /**/ } ',
                      'trait NormalTrait { /**/ } ',
                      'function NormalFunction( ) { /**/ } ',
                      '\'NormalConstant\'',
                     );

?>