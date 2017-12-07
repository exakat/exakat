<?php

$expected     = array('new DefinedAliasUndefinedClass( )',
                      'new NonexistantAlias( )',
                      'new UndefinedClass( )',
                     );

$expected_not = array('new DefinedLocalClass( )',
                      'new DefinedAlias( )',
                     );

?>