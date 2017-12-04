<?php

$expected     = array('$x instanceof \\UndefinedClass3',
                      '$x instanceof \\UndefinedTrait3',
                      '$x instanceof UndefinedClass3',
                      '$x instanceof \\UndefinedInterface3',
                      '$x instanceof UndefinedTrait3',
                      '$x instanceof UndefinedInterface3',
                     );

$expected_not = array('$x instanceof \\DefinedClass3',
                      '$x instanceof \\DefinedTrait3',
                      '$x instanceof DefinedClass3',
                      '$x instanceof \\DefinedInterface3',
                      '$x instanceof DefinedTrait3',
                      '$x instanceof DefinedInterface3',
                     );

?>