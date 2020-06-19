<?php


class x {
    public $c = '';
    static public $p = '';
    function foo() {
        $this->c = $_GET['x'];
        
        self::$p = $_GET['x'];
    }
}

$x = new x;
echo $x->c;

echo x::$p;

?>