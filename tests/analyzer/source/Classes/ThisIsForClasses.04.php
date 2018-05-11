<?php
function () {
    $this->ok; // Closure out of a class
};

function a() {
    $this->ko; // Function
};

class x {
    function method() {
        function () {
            $this->ko; // Closure in a class : automatic
        };
    }
}
?>