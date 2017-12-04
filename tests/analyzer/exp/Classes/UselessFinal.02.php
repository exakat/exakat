<?php

$expected     = array('final public function finalMethod( ) { /**/ } ',
                      'final public function finalMethod2( ) { /**/ } ',
                      'final public function finalMethod3( ) { /**/ } ',
                      'public final function finalMethod4( ) { /**/ } ',
                     );

$expected_not = array('public function nonFinalMethod()',
                      'public function nonFinalMethod2()',
                     );

?>