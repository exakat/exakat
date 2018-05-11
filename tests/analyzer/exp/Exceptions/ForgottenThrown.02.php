<?php

$expected     = array('new \\Exception( )',
                      'new \\myRuntimeException( )',
                     );

$expected_not = array('new \\Exception(\'assignement\')',
                      'new \\Exception(\'Parenthesis\'))',
                      'new \\myRuntimeException(\'assignement\')',
                      'new \\myRuntimeException(\'Parenthesis\'))',
                     );

?>