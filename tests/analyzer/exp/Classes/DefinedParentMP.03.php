<?php

$expected     = array('parent::definedPublicMethod( )',
                      'parent::definedProtectedMethod( )',
                     );

$expected_not = array('parent::undefinedMethod( )',
                      'parent::definedPrivateMethod( )',
                     );

?>