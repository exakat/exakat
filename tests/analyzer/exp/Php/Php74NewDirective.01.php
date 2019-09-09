<?php

$expected     = array('ini_get(\'zend.exception_ignore_args\')',
                      'ini_set("opcache.preload_user")',
                     );

$expected_not = array('ini_set("opcache.preload_user2")',
                     );

?>