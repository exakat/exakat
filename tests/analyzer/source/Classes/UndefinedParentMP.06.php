<?php

class a0 {    private $b0; }
class a1 extends a0 {    private $b1; }
class a2 extends a1 {    private $b2; }
class a3 extends a2 {    private $b3; 
    function c3() {
        parent::$b3;
        parent::$b2;
        parent::$b1;
        parent::$b0;
        parent::$b100; // do not exists
    }
}



?>