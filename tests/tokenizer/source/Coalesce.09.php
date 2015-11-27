<?php

function foo() {
        return $this->names ?? $this->names = array_keys($this->fields);
static $a;
    
    print_r($a);
    $a = new stdclass();
    $a->fields = [1,2,3];
    $a->names = null;
    
        return $a->names ?? $a->names = count($a->fields);
        
}

var_dump( foo());
var_dump( foo());
