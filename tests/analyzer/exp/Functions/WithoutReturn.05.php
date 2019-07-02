<?php

$expected     = array('function functionWithReturnInFunction($x) { /**/ } ',
                      'function functionWithReturnInClosure($x) { /**/ } ',
                      'function functionWithoutReturn($x) { /**/ } ',
                     );

$expected_not = array('function functionWithReturn($x) { /**/ } ',
                     );

?>