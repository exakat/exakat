<?php

$expected     = array('print \'<pre>\' . var_export($a, true) . \'</pre>\'',
                      'echo print_r($a, 1)',
                      'print \'<pre>\' . print_r($a, 1) . \'</pre>\'',
                      'print_r($a)',
                      'echo print_r($a)',
                      'var_dump($a)',
                     );

$expected_not = array('echo var_dump($a)',
                      'echo print_r($a)',
                     );

?>