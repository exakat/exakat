<?php

class x {
    private $pVoid;
    private string $pString = '2';
    private int $pInt = 1;
    
    function foo() {
        substr($this->pVoid, 0, 1);
        substr($this->pString, 0, 1);
        substr($this->pInt, 0, 1);
    }
}
?>