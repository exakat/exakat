<?php

$expected     = array('protected function foo2($a) { /**/ } ',
                      'public function a2(b $c) : ?int { /**/ } ',
                     );

$expected_not = array('public function a(b $c) : ?string { /**/ } ',
                      'abstract public function a(b $c) : ?string { /**/ } ',
                      'private function foo($a):array { /**/ } ',
                      'private function foo( ):array { /**/ } ',
                     );

?>