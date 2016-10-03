<?php

set_exception_handler();

set_exception_handler("foo");
set_exception_handler("\foo");
set_exception_handler("\\$foo");
set_exception_handler("\\".$foo);
set_exception_handler("foo3");
set_exception_handler("\$$foo3");
set_exception_handler("\$".$foo3);

set_exception_handler(array("foo", "bar"));
set_exception_handler(array("foo", "b$ar"));
set_exception_handler(array("foo", "b".$ar));

set_exception_handler('$foo::$bar');
set_exception_handler("$foo::$bar");
set_exception_handler("foo::bar");

set_exception_handler(array("foo3", "bar"));
set_exception_handler(array("foo3", "b$ar"));
set_exception_handler(array("foo3", "b".$ar));

set_exception_handler("foo3::bar");


function foo(Exception $e) {}
function foo2($e) {}
function foo3(Stdclass $e) {}

class foo { function bar(\Exception $e) {} }
class foo2 { function bar($e) {} }
class foo3 { function bar(Stdclass $e) {} }

?>