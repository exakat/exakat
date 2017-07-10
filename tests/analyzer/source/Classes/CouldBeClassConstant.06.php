<?php

class a  {
    public $modified = [];
    public $read = [];

    public function b()    {
        return empty($this->read);
    }

    protected function c(){
        $this->modified = [];
        $b = $this->read;
    }
}
