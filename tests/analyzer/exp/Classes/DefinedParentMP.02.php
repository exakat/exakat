<?php

$expected     = array('parent::$definedProtectedProperty',
                      'parent::$definedPublicProperty',
                     );

$expected_not = array('parent::$undefinedProperty',
                      'parent::$definedPrivateProperty',
                     );

?>