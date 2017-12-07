<?php

$expected     = array('function foo($xNone) { /**/ } ',
                      'public function foo($xPublic) { /**/ } ',
                      'protected function foo($xProtected) { /**/ } ',
                      'public function foo($xPublic) { /**/ } ',
                      'function foo($xNone) { /**/ } ',
                     );

$expected_not = array('function foo($yProtected) { /**/ } ',
                      'function foo($yPublic) { /**/ } ',
                      'function foo($yPublic2) { /**/ } ',
                      'function foo($yPublic3) { /**/ } ',
                     );

?>