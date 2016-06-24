<?php

abstract class x {
    abstract function withoutReturnType($a);

    abstract function withReturnType($b) : stdclass ;

    abstract static function privateWithoutReturnType($a);

    abstract static function privateWithReturnType($b) : stdclass ;

    static abstract function privateWithoutReturnTypeSA($a);

    static abstract function privateWithReturnTypeSA($b) : stdclass ;
}
?>