<?php

class v { }

class w extends v { }

class x extends w {
    public function foov() : \v {}
    public function foow() : \w {}
    public function foox() : \x {}
    public function fooy() : \y {}
    public function foos() : parent {}

    public function barv(\v $v){}
    public function barw(\w $w) {}
    public function barx(\x $s){}
    public function bary(\y $x) {}
    public function bars(self $s) : self {}
    
}
?>