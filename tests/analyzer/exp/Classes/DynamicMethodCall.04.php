<?php

$expected     = array('$object->${method}( )',
                      '$object->$method( )',
                      '$object->{$method}( )',
                     );

$expected_not = array('$object->$method[2]()',
                      '$object->${\'method\'}[2]()',
                     );

?>