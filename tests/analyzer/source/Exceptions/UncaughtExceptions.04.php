<?php

use B as DD;

class A extends \Exception {}
class B extends \A {}
class C extends \Exception {}


try {
    throw new A();
    throw new B();
    throw new C();
    throw new DD();
} catch (\A $e) {}

?>