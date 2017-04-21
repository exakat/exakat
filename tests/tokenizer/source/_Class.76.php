<?php

class x {
    function a () {
        new class {
            function bInAnomyous() {
                function foo() {}
            }
        } ;
    }
}
?>