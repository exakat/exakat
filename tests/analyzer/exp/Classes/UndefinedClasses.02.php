<?php

$expected     = array('UndefinedClass1::constante',
                      '\\UndefinedClass2::$property',
                      'A\\UndefinedClass3::method( )',
                     );

$expected_not = array('DefinedClass1::constante',
                      '\\DefinedClass2::$property',
                      'A\\DefinedClass3::method( )',
                      'A\\DefinedClass3::method( )',
                     );

?>