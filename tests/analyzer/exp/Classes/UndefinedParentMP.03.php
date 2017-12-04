<?php

$expected     = array('parent::undefinedMethod( )',
                      'parent::definedPrivateMethod( )',
                     );

$expected_not = array('parent::definedPublicMethod( )',
                      'parent::definedProtectedMethod( )',
                     );

?>