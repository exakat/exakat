<?php

class x {
    protected $a;
    protected $b = 2;

    public function __construct()
    {
        $this->a = true;
        $this->b   = md5(uniqid(null, true));
    }
}

?>