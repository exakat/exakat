<?php

$expected     = array('static function foo( ) : array { /**/ } ',
                     );

$expected_not = array('abstract function foo() : array ; ',
                      'function foo() : array; ',
                     );

?>