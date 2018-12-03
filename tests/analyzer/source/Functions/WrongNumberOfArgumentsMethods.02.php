<?php

class Foo {
    private function Bar($a, $b) {
        return $a + $b;
    }
    
    public function foobar() {
        $this->Bar(1);
        
        // Good amount
        $this->Bar(1, 2);
        
        // Too Many
        $this->Bar(1, 2, 3);
    }
}


?>