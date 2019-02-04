<?php

class x {
    function __construct() {
    
    }

    function __destruct() {
    
    }

    function a() {
    
    }
}

class y extends x {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
    
    }

    function a() {
        parent::a();
    }
}

?>