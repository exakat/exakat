<?php

class a {
    function __construct($a, $b = 1) { }
    function x($a, $b = 1) { }
}

new a;
new a();
new a(1);
new a(2, 3);
new a(4, 5, 6);
new a(7, 8, 9, 10);

?>