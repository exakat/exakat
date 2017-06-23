<?php

// Not a class
interface x {
    function __construct();
    function __destruct();
    function usableReturn();
}

class x {
    function __construct($x){return false;}
    function __destruct() {return true;}
    function usableReturnX() {return null;}
}


?>