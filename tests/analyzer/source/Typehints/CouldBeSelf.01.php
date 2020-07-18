<?php

class x {
    private x $x;
    private y $y;

    public function foox() : \x {}
    public function fooy() : \y {}
    public function foos() : self {}

    public function barx(\x $s){}
    public function bary(\y $x) {}
    public function bars(self $s) : self {}
    
}
?>