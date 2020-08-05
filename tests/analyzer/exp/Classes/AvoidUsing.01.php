<?php

$expected     = array('\'AvoidThisClass\'',
                      '$a instanceof AvoidThisClass',
                      'AvoidThisClass::constante',
                      'AvoidThisClass::$yes',
                      'AvoidThisClass::methodCall( )',
                      'new AvoidThisClass( )',
                     );

$expected_not = array('AvoidThisClass(2, 3, 4)',
                      '\'\\NS\\AvoidThisClass\'',
                      '\'NS\\AvoidThisClass\'',
                      'AvoidThisClass::methodCall( )',
                      'use NS\\AvoidThisClass as b',
                      '$a instanceof AvoidThisClass',
                      'class y extends AvoidThisClass implements AvoidThisClass { /**/ } ',
                      'AvoidThisClass::$yes',
                      'new AvoidThisClass( )',
                      'AvoidThisClass::constante',
                      'class y extends AvoidThisClass implements AvoidThisClass { /**/ } ',
                      'function x(AvoidThisClass $a) { /**/ } ',
                     );

?>