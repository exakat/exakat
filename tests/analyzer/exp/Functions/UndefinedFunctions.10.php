<?php

$expected     = array('\\b( )',
                      'namespace\\C( )',
                      'a( )',
                     );

$expected_not = array('\\a\\b( )',
                      'c( )',
                      'namespace\\b( )',
                     );

?>