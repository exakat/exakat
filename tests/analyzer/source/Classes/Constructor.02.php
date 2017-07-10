<?php 

class a {
    function __construct() {} // useless
}

class ab extends a {
    function __construct() {} // usefull
}

class b { }

class bb extends b {
    function __construct() {} // usefull
}

// last situation (c with construct, cb without) is worthless

class c { }

class cb extends b { }

class cbb extends cb {     
    function __construct() {} // useless because no constructor anywhere
}

class d { }

class dd extends d { } // no constructor anywhere!

interface i {
    function __construct(); // not constructor
}

trait t {
    function __construct() {} // useless because no constructor anywhere
}

function __construct($a) {} // This is a function

 ?>