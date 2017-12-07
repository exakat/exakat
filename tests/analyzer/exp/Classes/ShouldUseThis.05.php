<?php

$expected     = array('public function ClassMethodWithBody( ) { /**/ } ',
                      'static function ClassStaticMethodWithBody( ) { /**/ } ',
                     );

$expected_not = array('abstract function abstractClassMethod()',
                      'abstract public function abstractClassStaticMethod()',
                     );

?>