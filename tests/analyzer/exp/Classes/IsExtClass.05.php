<?php

$expected     = array('mysqli',
                      'Mongo(1)',
                      'mysqli( )',
                     );

$expected_not = array('$a->b',
                      '$a->b( )',
                      '$a( )',
                     );

?>