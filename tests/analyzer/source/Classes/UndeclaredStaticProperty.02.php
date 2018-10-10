<?php

class a { 
    static public $a = 1;
}

class aa extends a {
    function foo() {
        echo $this->a;
        echo self::$a;
    }
}

class b { 
    public $b = 1;
}

class bb extends b {

    function foo() {
        echo b::$b;
        echo $this->$b;
    }
}


?>