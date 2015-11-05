<?php

class B {
	
	public $b;
	public $c;
	public $d;
	public $e;

	public static $f, $g, $h;

    function x() {
        return new static + new static(2,3,4);
    }
}


?>