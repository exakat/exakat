<?php

class A {
    public static $a, $b;
}

unset( A::$a);
(unset) A::$b;

unset( A::$a[1]);
(unset) A::$b[1];

var_dump(a::$a);

?>
