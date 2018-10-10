<?php

class a { 
    function foo() {
        echo $this->a;
        echo self::$a;
    }
}

class aa extends a {
    static public $a = 1;
}

class b { 
    function foo() {
        echo b::$b;
        echo $this->$b;
    }
}

class bb extends b {
    public $b = 1;
}


?>