<?php

class x {
    private $twice = 1;
    private $once = 1;
    private $external = 1;
    var $onceButPublic = 1;
    
    public function one() {
        $this->once = 2;
        $this->twice = 2;
        $this->external = 2;
        $this->external = 2;
        $this->onceButPublic = 2;
    }
    
    function two() {
        $this->twice = 2;
        $a->$external = 2;
    }
}
?>