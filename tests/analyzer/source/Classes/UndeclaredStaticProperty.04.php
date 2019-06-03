<?php

class a { 
    function foo() {
        echo $this->a; // They are defined in the class below, so we can't find them
        echo self::$a; // They are defined in the class below, so we can't find them
    }
}

class aa extends a {
    static public $a = 1;
}

class b { 
    static private $c = 3;
    
    function foo() {
        echo b::$b;     // They are defined in the class below, so we can't find them
        echo $this->b; // They are defined in the class below, so we can't find them
        echo $this->c;  // declared static, used normal
    }
}

class bb extends b {
    public $b = 1;
}


?>