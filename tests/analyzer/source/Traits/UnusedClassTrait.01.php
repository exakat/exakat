<?php

trait t {
    function foo1() {}
    function foo2() {}
}

class a {
    function foo1() {}
    function foo2() {}
    function foo3() {}
}

class b1 extends a {
    use t; 
    
    function foo1() {}
    
    function goo() {
        print $this->foo1() + $this->foo2() + $this->foo3();
    }
}

class b2 extends a {
    use t; 
    
    function foo1() {}
    
    function goo() {
        print $this->foo1() + $this->foo3();
    }
}

class b3 extends a {
    function foo1() {}
    
    function goo() {
        print $this->foo1() + $this->foo2() + $this->foo3();
    }
}


?>