<?php

$expected     = array('parent::$undefinedProperty',
                      'parent::$definedProtectedProperty',
                      'parent::$definedPrivateProperty',
                     );

$expected_not = array('parent::$definedPrivateProperty',
                      'normal::$definedPrivateProperty',
                     );

?>