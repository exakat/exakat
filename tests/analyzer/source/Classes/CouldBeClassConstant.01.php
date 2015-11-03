<?php

class x {
    private $sBCIntegerR = 1;
    private $sBCIntegerW = 1;
    private $sBCIntegerRW = 1;
    private $sBCArray = array(1,2,3);

    public $sBCIntegerRp = 1;
    public $sBCIntegerWp = 1;
    public $sBCIntegerRWp = 1;
    public $sBCArrayp = array(1,2,3);
    
    function y() {
        $this->sBCIntegerW = $this->sBCIntegerR;
        $this->sBCIntegerRW++;

        foreach($this->sBCArray as $a) {
            $a++;
        };

        $this->sBCIntegerWp = $this->sBCIntegerRp;
        $this->sBCIntegerRWp++;

        foreach($this->sBCArrayp as $a) {
            $a++;
        };

    }
}

?>