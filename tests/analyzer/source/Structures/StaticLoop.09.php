<?php

class x {
    private $id, $id2;
//    private $y, $y2; // left undefined 
    static private $z, $z2;
    
    function foo() {
        while ($this->id > 10) { 
            echo $x;
        }

        while ($this->id2 > 10) { 
            echo 'a';
            ++$this->id2;
        }
    }

    function foo2() {
        while ($this->y > 10) { 
            echo $x;
        }

        while ($this->y2 > 10) { 
            echo 'a';
            ++$this->y2;
        }
    }

    static function foo3() {
        while (self::$z > 10) { 
            echo $x;
        }

        while (self::$z > 10) { 
            echo 'a';
            ++self::$z;
        }
    }

}
?>