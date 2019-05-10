<?php
class a {
    static public $s1 = array(); 
    
      public $a1 = array(); 
      public function m() {}
}

global $c;
$c = new a($d);

function foo() {
    global $c;

    $c->a1 = 3;
    $c->m();
}
?>