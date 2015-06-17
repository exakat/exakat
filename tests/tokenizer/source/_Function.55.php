<?php

function x($a) : Stdclass { }

function x2($a) { }

interface i {
    function x3($a) ;
    function x4($b) : Stdclass ;
}

function ($a) : Stdclass { };

function ($a) { };

function ($a) use ($y) : Stdclass { };

function ($a) use ($y) { };


function &xr($a) : Stdclass { }

function &x2r($a) { }

interface i {
    function &x3r($a) ;
    function &x4r($b) : Stdclass ;
}

function &($a) : Stdclass { };

function &($a) { };

function &($a) use ($y) : Stdclass { };

function &($a) use ($y) { };

?>