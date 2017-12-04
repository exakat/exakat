<?php

$expected     = array('global $$foo->bar',
                      'global $$foo1->bar1, $$foo2->bar2, $$foo3->bar3',
                     );

$expected_not = array('global ${$foo->bar}',
                     );

?>