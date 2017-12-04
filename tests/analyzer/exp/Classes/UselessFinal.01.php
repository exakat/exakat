<?php

$expected     = array('final public function finalMethod( ) { /**/ } ',
                     );

$expected_not = array('public function nonFinalMethod()',
                      'public function nonFinalMethod2()',
                      'public final function finalMethod2( ) { /**/ } ',
                     );

?>