<?php

// $this is only provided when Foo is constructed
class Foo {
    private $bar = null;
    private $data = array();
    
    static public function build($data) {
        $foo = new Foo($data);
        $foo->finalize();
    }

    private function __construct($data2) {
        // $this is provided too early
        $this->data = $data2;
    }
    
    function finalize() {
        $this->bar = new Bar($this);
    }
}

// $this is provided too early, leading to error in Bar
class Foo2 extends Foo {
    private $bar = null;
    private $data = array();
    
    function __construct($data) {
        // $this is provided too early
        $this->bar = new Bar($this);
        $this->data = $data;
    }
}

class Bar {
    function __construct(Foo $foo) {
        // the cache is now initialized with a wrong 
        $this->cache = $foo->getIt();
    }
}

?>