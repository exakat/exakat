<?php

$expected     = array('function nonPPP( ) { /**/ } ',
                      'abstract function nonPPPabstract( ) ;',
                     );

$expected_not = array('protected static function protectedm( ) { /**/ } ',
                      'private static function privatem( )  { /**/ } ',
                      'public static function publicm( ) { /**/ } ',
                     );

?>