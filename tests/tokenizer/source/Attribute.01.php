<?php

@@Attribute
@@Attribute2
@@Test(3 + 4)
class x implements i, i {

    @@PropertyAttribute
    public int $foo;

    @@ConstAttribute
    public const BAR = 1;

    @@MethodAttribute
    public function foo() {}
}

$object = new @@AnonymousAttribute class () { /* … */ };

?>