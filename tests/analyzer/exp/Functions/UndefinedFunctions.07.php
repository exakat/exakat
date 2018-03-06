<?php

$expected     = array('a( )',
                     );

$expected_not = array('$o->foo[$bar]( )',
                      '$foo->bar[0]( )',
                      '{$increment_tracker($tracker)}',
                     );

?>