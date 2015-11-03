<?php

b::a();
b::d();
b::e();

E::a();
E::d();
E::e();

\E::a();
\E::d();
\E::e();

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