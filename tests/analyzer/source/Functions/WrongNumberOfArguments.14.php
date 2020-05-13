<?php
new class { public function __construct($first) {} public static function test(int $x) { return $x;}};
new class () { public function __construct($first) {} public static function test(int $x) { return $x;}};
new class ($a) { public function __construct($first) {} public static function test(int $x) { return $x;}};
new class ($a, $b) { public function __construct($first) {} public static function test(int $x) { return $x;}};


class a {
    function __construct($b) {}
}

new A;
new A();
new A($a);
new A($a, $b);

?>