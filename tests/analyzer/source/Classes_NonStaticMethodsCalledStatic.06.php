<?php

C::a();
C::d();
C::e();

class b {
    public function a() {}
    public static function d() {}
    public function e() {}
}

class E {
    public static function a() {}
    public static function d() {}
    public function e() {}
}

?>