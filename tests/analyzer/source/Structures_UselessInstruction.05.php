<?php

class x {
    function z() {
        $x = function($a) { $this->ok2 = 2; };
    }
}

// closure is defined but not used
function () {
    $x = 3;
};

?>