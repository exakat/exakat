<?php

$expected     = array('list($a, $b, $d,  )',
                      'array(\'b\', $d,  )',
                     );

$expected_not = array('list($a, $b, $c,  )',
                      'array(\'b\', $c,  )',
                      '[\'b\', $c,  ]',
                     );

?>