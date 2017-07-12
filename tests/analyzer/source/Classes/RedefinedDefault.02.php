<?php

class x {
    public $notDefined;
    protected $redefined = 2;

    public function __construct() {
        $this->redefined = 2;
        $this->notDefined = 3;
    }

}