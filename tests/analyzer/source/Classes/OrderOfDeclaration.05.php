<?php

// Good
class xGoodOrder {
    use traits;
    
    const CONSTANTS = 1;
    const CONSTANTS2 = 1;
    const CONSTANTS3 = 1;
    
    private $property = 2;
    private $property2 = 2;
    private $property3 = 2;
    
    public function foo() {}
    public function foo2() {}
    public function foo3() {}
    public function foo4() {
        $this->virtualproperty = 1;
    }
}

// Good
class xWrongOrder {
    use traits;
    
    const CONSTANTS = 1;
    
    private $property = 2;
    private $property2 = 2;
    private $property3 = 2;
    
    public function foo() {}
    public function foo2() {}
    public function foo3() {}
    public function foo4() {
        $this->virtualproperty = 1;
    }

    const CONSTANTS2 = 1;
    const CONSTANTS3 = 1;
}

?>