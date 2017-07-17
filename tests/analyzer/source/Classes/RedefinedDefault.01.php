<?php

class x {
    public $redefined = 1;
    protected $redefined2 = 2;

    public $Notredefined = 1;
    public $redefinedInAnotherMethod = 1;
    public $updated = 1;
    
    public function __construct() {
        $this->redefined = 2;
        $this->redefined2 = 'C';

        $this->updated += time();
    }

    public function b() {
        $this->redefinedInAnotherMethod = 2;
    }

}