<?php
class a {
    public $a1 = array(); 
}

class b{

    public function __construct(){
        $b = new a;
        $b = new \a;
        $b = new \a();
        $b = new a();
        $b->a1 = 3;
    }
}
?>