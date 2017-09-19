<?php

$expected     = array('set_exception_handler("cfoo3")',
                      'set_exception_handler("cfoo")',
                      'set_exception_handler("\cfoo")',
                      'set_exception_handler("foo::bar")',
                      'set_exception_handler("foo3::bar")',
                      'set_exception_handler(array("foo", "bar"))', 
                      'set_exception_handler(array("foo3", "bar"))'
                      );

$expected_not = array('set_exception_handler("foo::b$ar")',
                      'set_exception_handler("foo::b" . $ar)',
                      'set_exception_handler("foo3::bar")',
                      'set_exception_handler("$foo::$bar")',

);

?>