<?php

class a {
    function nonStatic() {}
    static function reallyStatic() {}
}

class b {
    function nonStaticInBClass() {}
    static function reallyStaticInBClass() {}
}

class x {
function y() {
a::nonStatic();
a::reallyStatic();
a::doesntExist();
a::nonStaticInBClass();
a::reallyStaticInBClass();

classDoesntExist::nonStatic();
classDoesntExist::reallyStatic();
classDoesntExist::doesntExist();
classDoesntExist::nonStaticInBClass();
classDoesntExist::reallyStaticInBClass();
}}
?>