<?php

trait a { 
    static public $a = 1;
}

class aa  {
    use a;
    function foo() {
        echo $this->a;
        echo self::$a;
    }
}

trait b { 
    public $b = 1;
}

class bb {
    use b;
    
    function foo() {
        echo b::$b;
        echo $this->$b;
    }
}


?>