<?php

use Exception as X;

class MyException extends \Exception {
    function __construct() {
        parent::__construct($myexception);
    }
}

class MyException2 extends Exception {
    function __construct() {
        parent::__construct($myexception2);
    }
}

class MyException3 extends Exxeption {
    function __construct() {
        parent::__construct($myexception3);
    }
}

class MyException4 extends X {
    function __construct() {
        parent::__construct($myexception4);
    }
}

class MyException5 extends MyException4 {
    function __construct() {
        parent::__construct($myexception5);
    }
}

?>