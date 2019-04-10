<?php

class A  {
    public $modified0 = [];
    public $modified1 = [];
    public $modified2 = [];
    public $read = [];

    public function b()    {
        return empty($this->read);
    }

    protected function C($e) {
        $this->modified0[] = 3;
        $this->modified1['c'][] = 3;
        $this->modified2['c']["3"][] = 3;
        $b = $this->read['c'];
    }
}
