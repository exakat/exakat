<?php

class a {
    static function x($a, $b = 1) { }
}

A::x();
A::x(1);
A::x(2, 3);
A::x(4, 5, 6);
A::x(7, 8, 9, 10);

?>