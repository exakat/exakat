<?php

$expected     = array('eval("some hardocded code")',
                      'eval($d[$e])',
                      'eval(" $a $b")',
                      'eval($a . $b)',
                     );

$expected_not = array('CONSTANT',
                      '\\CONSTANT',
                      'eval(\\CONSTANT)',
                      'eval(CONSTANT)',
                     );

?>