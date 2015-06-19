<?php

class x {
    
    public function __construct($a) {
        parent::__construct($a);
        
        self::b();
    }
    
    public function b() {}
}
?>