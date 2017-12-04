<?php

$expected     = array('\\RuntimeException(\'Error while processing\')',
                      '\\Zend\\Filter\\Exception\\ExtensionNotLoadedException( )',
                      '\\Zend\\Filter\\Exception\\ExtensionNotLoadedException',
                      'ExceptionHiddenByAlias',
                     );

$expected_not = array('\\NotZend\\Filter\\Exception\\ExtensionNotLoadedException( )',
                      'foo( )',
                     );

?>