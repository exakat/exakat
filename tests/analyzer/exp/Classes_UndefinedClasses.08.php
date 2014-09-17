<?php

$expected     = array('new NonexistantAlias( )',
                      'new UndefinedAlias( )',
                      'new UndefinedClass( )');

$expected_not = array('new DefinedClass( )',
                      'new DefinedAlias( )',
                      );

?>