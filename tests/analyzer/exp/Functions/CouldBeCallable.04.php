<?php

$expected     = array('function OneAndOk($b) { /**/ } ',
                      'function cc($id, $servers) { /**/ } ',
                     );

$expected_not = array('public function cc($id, $servers) { /**/ } ',
                      'function OneButNotArg( ) { /**/ } ',
                      'function None( ) { /**/ } ',
                     );

?>