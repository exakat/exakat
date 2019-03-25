<?php

class x1 {
    function __call($a1, $b1) {
        $this->$a1(...$b1);
    }

    static function __callstatic($a1, $b1) {
        self::$a1(...$b1);
    }
}


class x2 {
    function __call($a2, $b2) {
        if (!method_exists($this, $a2))  {
            return;
        }
        $this->$a2(...$b2);
    }

    static function __callstatic($a2, $b2) {
        if (!method_exists($this, $a2))  {
            return;
        }
        $this->$a2(...$b2);
    }
}

class x3 {
    function __call($a3, $b3) {
        if (!method_exists($this, $a3))  {
            return;
        }
        call_user_func(array($this, $a3), $b3);
    }

    static function __callstatic($a3, $b3) {
        if (!method_exists($this, $a3))  {
            return;
        }
        call_user_func(array('self', $a3), $b3);
    }
    
    static function foo($a) {
        print $a;
    }
}

class x4 {
    function __call($a4, $b1) {
        $this->q->$a4(...$b1);
    }

    static function __callstatic($a4, $b1) {
        self::$q::$a4(...$b1);
    }
}

class x5 {
    static function __callstatic($a5, $b1) {
        parent::$a5(...$b1);
    }
}

class x6 {
    static function __callstatic($a6, $b1) {
        static::$a6(...$b1);
    }
}


?>