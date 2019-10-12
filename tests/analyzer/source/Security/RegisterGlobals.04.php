<?php

class y {
    function foo() {
        foreach($input as $k1 => $v) {
            $this->$k1 = $v;
        }
    }

    function foo2() {
        foreach($_POST as $k2 => $v) {
            $this->$k2 = $v;
        }
    }

    function foo3() {
        foreach($_COOKIE as $k3 => $v) {
            self::$k3 = $v;
        }
    }

    function foo4() {
        foreach($args ?? $_POST as $k4 => $v) {
            self::$k4 = $v;
        }
    }

    function foo5() {
        foreach($_REQUEST ?? [] as $k5 => $v) {
            self::$k5 = $v;
        }
    }

    function foo6() {
        foreach(CONFIG ? $_GET : $_POST as $k6 => $v) {
            self::$k6 = $v;
        }
    }

    function foo7() {
        foreach(CONFIG ? $_GET : $_POST as $k7 => $v) {
            $k7 = $v;
        }
    }

}

(new y)->foo($_GET);
?>