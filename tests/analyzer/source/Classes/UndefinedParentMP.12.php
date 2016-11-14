<?php

use Exception as X;

class MyException extends \Exception {
    function __construct() {
        parent::__construct();
    }
}

class MyException2 extends Exception {
    function __construct() {
        parent::__construct();
    }
}

class MyException3 extends Exxeption {
    function __construct() {
        parent::__construct();
    }
}

class MyException4 extends X {
    function __construct() {
        parent::__construct();
    }
}

?>