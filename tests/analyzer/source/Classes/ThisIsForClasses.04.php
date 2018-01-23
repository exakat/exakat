<?php
function () {
    $this->ko; // Closure out of a class!!
};

class x {
    function method() {
        function () {
            $this->ko; // Closure in a class!!
        };
    }
}
?>