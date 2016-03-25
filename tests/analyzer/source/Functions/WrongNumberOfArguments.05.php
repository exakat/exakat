<?php

class x0 {
    function __construct() {}
}

new x0();
new x0(1);
new x0(1,2);
new x0(1,2, 3);

class x1 {
    function __construct($a) {}
}

new x1();
new x1(1);
new x1(1,2);
new x1(1,2, 3);

class x2 {
    function __construct($a1, $a2) {}
}

new x2();
new x2(1);
new x2(1,2);
new x2(1,2, 3);

class x3 {
    function __construct($a1, $a2, $a3) {}
}

new x3();
new x3(1);
new x3(1,2);
new x3(1,2, 3);
