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
    function foo() {
        echo a::$b;     // They are defined in the class below, so we can't find them
        echo $this->$b; // They are defined in the class below, so we can't find them
    }
}

class bb extends b {
    public $b = 1;
}


?>