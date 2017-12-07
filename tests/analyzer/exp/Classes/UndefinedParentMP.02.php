<?php

$expected     = array('parent::$undefinedProperty',
                      'parent::$definedPrivateProperty',
                     );

$expected_not = array('parent::$definedProtectedProperty',
                     );

?>