<?php

use foo2 as foo, foo3 as foo4;

class foo3 {
    function bar() {
        echo 'I am not static!';
    }

    static function staticBar() {
        echo 'I am static!';
    }
    
    function baz() {
        self::bar();
        static::bar();

        self::staticBar();
        static::staticBar();
    }
}

class foo2 extends foo3 { 
    function baz() {
        parent::bar();
    }
}

foo4::staticBar();
foo3::staticBar();
foo2::staticBar();
foo::staticBar();

foo4::bar();
foo3::bar();
foo2::bar();
foo::bar();

?>
