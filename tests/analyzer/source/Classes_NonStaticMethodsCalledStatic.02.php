<?php

class y {
    public function __construct($a) {}

}

class x extends y {
    
    public function __construct($a) {
        parent::__construct($a);
        
        self::b();
        UndefinedClass::Yes();
    }
    
    public function b() {}
}
?>