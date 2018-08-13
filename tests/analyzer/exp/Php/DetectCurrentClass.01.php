<?php

$expected     = array('__CLASS__',
                      '__class__',
                      'get_called_class( )',
                     );

$expected_not = array('B::__CLASS__',
                      'get_called_class( )',
                     );

?>