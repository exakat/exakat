<?php

$expected     = array('UndefinedClass::y( )',
                      'UndefinedAlias::y( )',
                      'NonexistantAlias::y( )',
                     );

$expected_not = array('DefinedAlias::y( )',
                      'DefinedClass::y( )',
                     );

?>