<?php
class x {
    public static function foo() {
        echo __METHOD__;
    }
}

//call_user_func([\x::class, 'foo']);die();

// designate the foo method in the x class
$f = ['x', 'foo',3];
$f();

$f = [\x::class, 'FOO'];

const A = 'foo';
const B = '\\x';

$f = [\x::class, A];
$f = [B, A];

$a = new x;
$f = [$a, 'foo'];

// Nope
$f = [\x, 'foo'];

?>