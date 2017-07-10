<?php

class A  {
    public $modified = [];
    public $read = [];

    public function b()    {
        return empty($this->read);
    }

    protected function C($e) {
        $this->modified['c'][] = 3;
        $b = $this->read['c'];
    }
}
