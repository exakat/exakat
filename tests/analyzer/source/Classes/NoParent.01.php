<?php

class x {
    function foo() {
        parent::foo();

        function () {
            parent::foo5();
        };

    }
}

class x2 extends x {
    function foo() {
        parent::foo2();
    }
}

class x3 extends x2 {
    function foo() {
        function () {
            parent::foo3();
        };

        static function () {
            parent::foo4();
        };
    }
}

?>