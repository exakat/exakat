<?php

$a = new b();
$a = new $b();
$a = new $b['f']();
$a->usedMethod();


$a = new \c();
$a = new \d();

class b {
    public function __construct($b) {}
    public function unusedMethod() {}
    public function usedMethod() {}
}

class c {
    public function __construct($c) {}
}

class d {
    public function __destruct() {}
}

?>