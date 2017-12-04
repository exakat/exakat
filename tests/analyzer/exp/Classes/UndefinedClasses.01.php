<?php

$expected     = array('new A\\UndefinedClass3( )',
                      'new \\UndefinedClass2( )',
                      'new UndefinedClass1( )',
                     );

$expected_not = array('new A\\DefinedClass3( )',
                      'new \\DefinedClass2( )',
                      'new DefinedClass1( )',
                     );

?>