<?php 

class a {}

class a1 extends a {}
class a2 extends a {}
class a3 extends a {}
class a4 extends a {}
class a5 implements a {}
class a6 extends a {}
class a7 extends a {}
class a8 extends a {}
class a9 extends a {}
class a10 extends a {}
class a11 extends a {}
class a12 extends a {}
class a13 extends a {}
class a14 extends a {}
class a15 extends a {}
class a16 extends a {}
class a17 extends a {}

class b {
    public $c = 1;
    
    function foo(b $c) {
        $this->c = 3;
    }

    function foo2(b $c) : self {
        $this->c = 3;
        $this->d = new static();
        $this->d = new self();
        $this->d = new static;
    }

    function foo3(b $c) : b {
        $this->c = 3;
        $this->d = new static();
        $this->d = new self();
        $this->d = new static;
        $this->d = new $this;
    }
}



 ?>