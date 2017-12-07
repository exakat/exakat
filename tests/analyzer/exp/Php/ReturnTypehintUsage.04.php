<?php

$expected     = array('abstract function withReturnType($b) : stdclass ;',
                      'static abstract function privateWithReturnTypeSA($b) : stdclass ;',
                      'abstract static function privateWithReturnType($b) : stdclass ;',
                     );

$expected_not = array('abstract function withoutReturnType($a) ;',
                      'static abstract function privateWithReturnTypeSA($b) ;',
                      'abstract static function privateWithoutReturnType($a) ;',
                     );

?>