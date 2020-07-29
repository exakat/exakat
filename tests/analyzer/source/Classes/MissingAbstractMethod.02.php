<?php

class c extends a {
    public function fooC() {}
    public function fooD() {{}}
}

class b extends a {
    public function fooD() {{}}
}

abstract class a {
    abstract function fooC() ;
    public function fooD() {{}}
}

