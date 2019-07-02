<?php

$expected     = array('protected function foo($yProtected2) { /**/ } ',
                      'protected function foo($yPublic2) { /**/ } ',
                      'private function foo($yPrivate) { /**/ } ',
                      'private function foo($yProtected) { /**/ } ',
                      'private function foo($yPublic) { /**/ } ',
                      'private function foo($yPrivate2) { /**/ } ',
                      'protected function foo($yPublic3) { /**/ } ',
                      'protected function foo($yProtected3) { /**/ } ',
                      'private function foo($yPrivate3) { /**/ } ',
                     );

$expected_not = array('function foo($yProtected) { /**/ } ',
                      'function foo($yPublic) { /**/ } ',
                      'function foo($yPublic2) { /**/ } ',
                      'function foo($yPublic3) { /**/ } ',
                     );

?>