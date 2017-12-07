<?php

$expected     = array('set_exception_handler("cfoo3")',
                      'set_exception_handler("\\cfoo3")',
                      'set_exception_handler("cfoo")',
                      'set_exception_handler("\\cfoo")',
                      'set_exception_handler("foo::bar")',
                      'set_exception_handler("foo3::bar")',
                      'set_exception_handler(function (Stdclass $a) { /**/ } )',
                      'set_exception_handler(function (Error $a) { /**/ } )',
                      'set_exception_handler(array("foo3", "bar"))',
                      'set_exception_handler(array("foo", "bar"))',
                     );

$expected_not = array('set_exception_handler("foo2")',
                      'set_exception_handler("\\foo2")',
                      'set_exception_handler("foo2::bar")',
                      'set_exception_handler(function ($a) { /**/ } )',
                      'set_exception_handler(function (Throwable $a) { /**/ } )',
                     );

?>