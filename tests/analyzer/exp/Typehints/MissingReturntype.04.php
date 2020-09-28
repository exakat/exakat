<?php

$expected     = array('public function run5( ) : string { /**/ } ',
                      'public function run6( ) : Command { /**/ } ',
                     );

$expected_not = array('public function run( ) : Command { /**/ } ',
                      'public function run2( ) : \\Command { /**/ } ',
                      'public function run3( ) : self { /**/ } ',
                      'public function run4( ) : parent { /**/ } ',
                     );

?>