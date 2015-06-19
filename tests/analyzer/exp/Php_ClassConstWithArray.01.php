<?php

$expected     = array('const isArray2C = [ 4, 5, 6 ]',
                      'const isArray2AC = [ 4, 5, 6 ]',
                      'const isArray2I = [ 4, 5, 6 ]',
                      'const isArrayC = array(4, 5, 6)',
                      'const isArrayAC = array(4, 5, 6)',
                      'const isArrayI = array(4, 5, 6)',
);

$expected_not = array('const isNotArrayC = 4',
                      'const isNotArrayAC = 4',
                      'const isNotArrayI = 4',
                      );

?>