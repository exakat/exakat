<?php

$expected     = array('parent::$definedPrivateProperty',
                      'parent::$undefinedProperty',
                     );

$expected_not = array('normal::$definedPrivateProperty',
                      'parent::$definedProtectedProperty',
                     );

?>