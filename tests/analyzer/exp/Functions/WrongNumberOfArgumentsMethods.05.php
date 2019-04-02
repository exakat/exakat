<?php

$expected     = array('$a->finish(1, 2, 3, 4, 5)',
                      '$a->finish(1, 2, 3, 4)',
                      '$a->finish(1, 2, 3)',
                      '$a->finish(1, 2)',
                      '$a->finish( )',
                     );

$expected_not = array('$a->finish(1 )',
                     );

?>