<?php 

class a {
public $p = 1;
}

class b {
public function p() { return 3; }
}

class c{}

var_dump((new a)->p == 2);

(new b)->p() == 3;

$c = (new c);

?>