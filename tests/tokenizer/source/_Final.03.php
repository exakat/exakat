<?php

abstract class x { 
    final function a() {}
    public final function b() {}
    static final function c() {}

    static final private function c1() {}
    final static private function c2() {}
    private static final function c3() {}
    static private final function c4() {}
    final private static function c5() {}
    private final static function c6() {}
}

?>