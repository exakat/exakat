<?php

interface i
{
    public function id1() : self;
    public function id2() : i;
    public function id3() : i;
    public function id4() : a;
}

class C implements I
{
     public function id1() : self { }
     public function id2() : self { }
     public function id3() : I { }
     public function id4() : I { }
}

?>