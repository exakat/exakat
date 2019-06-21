<?php

class x {
    function __construct() {}
}

class y {
    function y() {}
}

class zz {
    function __construct() {}
}
class z extends zz { }

class aa {
    function __construct() {}
    function aa() {}
}


new x;
new y;
new zz;
new aa;

?>