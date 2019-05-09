<?php

class x1 {
    static function foo1($a, $b = 1) {}
}
$x1::foo1(1, 2);

class x2 {
    static function foo2($a, $b = 1) {}
}
x2::foo2(1, 2);
x2::foo2(1, 2);

class x3 {
    static function foo3($a, $b = 1) {}
}
x3::foo3(1, 2);
x3::foo3(1, 2);
x3::foo3(1, 2);

class x3a {
    static function foo3b($a, $b = 1) {}
}

x3a::foo3b(1, 2);
x3a::foo3b(1, 2);
x3a::foo3b(1);

?>
