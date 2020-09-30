<?php

$expected     = array('global $$x',
                      'global $$f',
                      'global ${y[2]}',
                      'global ${$foo->bar2}',
                      'global $$foo->bar',
                     );

$expected_not = array('global $x',
                     );

?>