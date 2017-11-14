<?php

class a {
    static public function  allStatic() {}
    function noneStatic() {}
    function mixtedStatic() {}
}

class b {
    public static function allStatic() {}
    public function noneStatic() {}
    static function mixtedStatic() {}
}


?>