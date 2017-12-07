<?php

$expected     = array('public final function BmethodFinal2( ) { /**/ } ',
                      'final public function BmethodFinal( ) { /**/ } ',
                      'final public function AmethodFinal( ) { /**/ } ',
                      'public final function AmethodFinal2( ) { /**/ } ',
                     );

$expected_not = array('public function AmethodNonFinal() { /**/ } ',
                     );

?>