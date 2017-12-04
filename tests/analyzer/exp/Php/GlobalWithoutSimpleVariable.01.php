<?php

$expected     = array('global $$x',
                      'global $$f',
                      'global ${y[2]}',
                      'global ${$foo->bar}',
                      'global $$foo->bar',
                     );

$expected_not = array('global $x',
                     );

?>