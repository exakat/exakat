<?php

interface i1 {
    function i1() ;
}

interface i2 {
    function i2() ;
}

class aOK1 implements i1, i2 {
    function i1 () {}
    public function i2 () {}
}

class aOK2 implements i1, i2 {
    function i1 () {}
    public function i2 () {}
    private function a3 () {}
}

class aOK3 implements i1, i2 {
    function i1 () {}
    public function i2 () {}
    protected function a3 () {}
}

class aKO1 implements i1, i2 {
    function i1 () {}
    public function i2 () {}
    function a3 () {}
}

class aKO2 implements i1, i2 {
    function i1 () {}
    public function i2 () {}
    public function a3 () {}
}
