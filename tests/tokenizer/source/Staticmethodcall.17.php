<?php

new self;
new parent;
new static;

new SELF;
new PARENT;
new STATIC;

new self();
new parent();
new static();

new self(1);
new parent(1);
new static(1);

self::A;
parent::A;
static::A;

self::m();
parent::m();
static::m();

self::$a;
parent::$a;
static::$a;

class x {
    function foo() {
        new self;
        new parent;
        new static;
        
        self::Ac;
        parent::Ac;
        static::Ac;
        
        self::mc();
        parent::mc();
        static::mc();
        
        self::$ac;
        parent::$ac;
        static::$ac;
    }
}

trait t {
    function foo() {
        new self;
        new parent;
        new static;
        
        self::At;
        parent::At;
        static::At;
        
        self::mt();
        parent::mt();
        static::mt();
        
        self::$at;
        parent::$at;
        static::$at;
    }
}


?>