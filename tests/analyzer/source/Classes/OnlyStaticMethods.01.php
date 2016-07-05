<?php

class Only1StaticMethods{
    static function a() {}
}

class Only2StaticMethods{
    static function a() {}
    static function a2() {}
}

class Only3StaticMethods{
    static function a() {}
    static function a2() {}
    static function a3() {}
}

class onlyStaticMethods3Property {
    private $a = 2;
    static function a2() {}
    static function a3() {}
}

class NotOnlyStaticMethods{
    function a() {}
    static function a2() {}
    static function a3() {}
}

class NotOnlyStaticMethods2{
    final function a() {}
    static function a2() {}
    static function a3() {}
}

?>