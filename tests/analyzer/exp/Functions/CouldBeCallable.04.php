<?php

$expected     = array('function OneAndOk($b) { /**/ } ',
                     );

$expected_not = array('public function cc($id, $servers) { /**/ } ',
                      'function OneButNotArg( ) { /**/ } ',
                      'function None( ) { /**/ } ',
                     );

?>