<?php

interface i {
    function fooint(); 
    function foobah(); 
}

class x implements i {
    function foox() {}
    function fooint() {return 1;}
    function foobah() {}
}

class y extends x {
    function foox() {}
    function fooint() {return 1;}
    function foobah() {}
}

class y2 extends x {
    function fooy() {}
    function fooint() { return 1;}
    function foobah() {}
}
