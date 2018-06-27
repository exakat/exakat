<?php

// reference : toujours la
// visibility : restreinte
// default : maintained or dropped
// type : identique or added

// nullable ? 
// variadic : ignored


class a {
    function fooReference1($a, $b){}
    function fooReference2($a, &$b){}
    function fooReference3(&$a, &$b){}
    function fooReference4(&$a, &$b){}
    
    function argcount1($a){}
    function argcount2($a){}
    function argcount3($a, $b) {}
    function argcount4($a, $b) {}
    
    function fooVisibility1($a, $b){}
    private function fooVisibility2($a, $b){}
    protected function fooVisibility3($a, $b){}
    public function fooVisibility4($a, $b){}

    function fooTypehint1($a){}
    function fooTypehint2(B $a){}
    function fooTypehint3(C $a){}
    function fooTypehint4(A $a){}

    function foodefaultvalue1($a){}
    function foodefaultvalue2($a){}
    function foodefaultvalue3($a = 2){}
    function foodefaultvalue4($a = 1){}

    function fooReturnTypehint1($a){}
    function fooReturnTypehint2($a) : A {}
    function fooReturnTypehint3($a) : B {}
    function fooReturnTypehint4($a) : B {}

    function fooNullableTypehint1($a){}
    function fooNullableTypehint2(?B $a){}
    function fooNullableTypehint3(C $a){}
    function fooNullableTypehint4(?A $a){}

    function fooNullableReturnTypehint1($a){}
    function fooNullableReturnTypehint2($a) : ?A {}
    function fooNullableReturnTypehint3($a) : B {}
    function fooNullableReturnTypehint4($a) : ?B {}

}
?>