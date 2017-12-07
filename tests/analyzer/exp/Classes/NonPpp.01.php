<?php

$expected     = array('function nonPPP( ) { /**/ } ',
                      'final function finalm( ) { /**/ } ',
                      'static function staticm( ) { /**/ } ',
                      'abstract function abstractm( ) ;',
                      'var $varp',
                      'static $staticp',
                     );

$expected_not = array('function normalFunction( ) { /**/ } ',
                     );

?>