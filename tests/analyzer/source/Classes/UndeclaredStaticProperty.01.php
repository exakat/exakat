<?php

class a { 
    static public $a = 1;
    
    function foo() {
        echo $this->a;
        echo self::$a;
    }
}

class b { 
    public $b = 1;

    function foo() {
        echo b::$b;
        echo $this->$b;
    }
}


?>