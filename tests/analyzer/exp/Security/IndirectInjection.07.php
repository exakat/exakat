<?php

$expected     = array('echo $x->c',
                      'echo x::$p',
                     );

$expected_not = array('$c = \'\'',
                     );

?>