<?php

set_exception_handler();

set_exception_handler("foo");
set_exception_handler("\foo");
set_exception_handler("foo3");
set_exception_handler("\foo3");

set_exception_handler(array("foo", "bar"));
set_exception_handler("foo::bar");
set_exception_handler(array("foo3", "bar"));
set_exception_handler("foo3::bar");

set_exception_handler(function (Error $a) {});
set_exception_handler(function (Stdclass $a) {});

set_exception_handler("foo2");
set_exception_handler("\foo2");

set_exception_handler(array("foo2", "bar"));
set_exception_handler("foo2::bar");

set_exception_handler(function ($a) {});
set_exception_handler(function (Throwable $a) {});


function foo(Exception $e) {}
function foo2($e) {}
function foo3(Stdclass $e) {}

class foo { function bar(\Exception $e) {} }
class foo2 { function bar($e) {} }
class foo3 { function bar(Stdclass $e) {} }

?>