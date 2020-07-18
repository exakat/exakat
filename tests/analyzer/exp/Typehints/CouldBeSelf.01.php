<?php

$expected     = array('private x $x',
                      '\x $s',
                      'public function foox( ) : \x { /**/ } ',
                     );

$expected_not = array('private y $y',
                      '\y $x',
                      'public function fooy() : \y { /**/ } ',
                      'public function foos() : self { /**/ } ',
                     );

?>