<?php

$expected     = array('function FooRequireOnce( ) { /**/ } ',
                      'function FooExtract( ) { /**/ } ',
                      'function FooIncludeOnce( ) { /**/ } ',
                      'function FooRequire( ) { /**/ } ',
                      'function FooExtractInclude( ) { /**/ } ',
                      'function FooEval( ) { /**/ } ',
                      'function FooInclude( ) { /**/ } ',
                     );

$expected_not = array('function Foo( ) { /**/ } ',
                     );

?>