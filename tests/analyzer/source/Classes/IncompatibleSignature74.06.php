<?php

interface i
{
    public function id1() : self;
    public function id2() : self;
    public function id3() : I;
    public function id4() : self;
    public function id5() : A;
}

class C implements I
{
     public function id1() : C
     {
        var_dump(new ImplementsFoo instanceof C);
        var_dump(new ImplementsFoo instanceof I);
        var_dump(new ImplementsFoo instanceof Self);

        return $this;
     }

     public function id2() : I{ }
     public function id3() : I{ }
     public function id4() : Self{ }
     public function id5() : Self{ }
}

?>