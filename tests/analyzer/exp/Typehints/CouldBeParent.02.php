<?php

$expected     = array('\\v $v',
                      '\\w $w',
                      'public function foov( ) : \\v { /**/ } ',
                      'public function foow( ) : \\w { /**/ } ',
                     );

$expected_not = array('\\x $x',
                      'public function foox( ) : \\x { /**/ } ',
                     );

?>