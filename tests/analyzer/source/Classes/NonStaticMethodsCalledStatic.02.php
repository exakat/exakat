<?php

class y {
    public function __construct($a) {}
    public function b() {}

}

        y::b(1);


class x extends y {
    
    public function __construct($a) {
        parent::__construct($a);
        
        self::b();
        y::b();
        UndefinedClass::Yes();
    }
    
    public function b() {}
}
?>