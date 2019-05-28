<?php

class x {
    static function methodx() {}
}

class y extends x {
    static function methody() {}
}

class z extends y {
    static function methodz() {}

    function y() {
        echo x::methodx();
        echo \x::methodx();

        echo y::methody();
        echo \y::methody();

        echo z::methodz();
        echo \z::methodz();

        echo b::method();
        echo \b::method();
    }
}

?>