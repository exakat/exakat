<?php

$expected     = array('abstract function withReturnType($b) : stdclass', 
                      'static abstract function privateWithReturnType($b) : stdclass');

$expected_not = array('abstract function withoutReturnType($a)',
                      'abstract static function privateWithoutReturnType($a)');

?>