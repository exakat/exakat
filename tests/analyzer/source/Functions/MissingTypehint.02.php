<?php

function foo($a1) : false|null|A|B {}
function foo2(false|null|A|B  $a2) : void {}
function foo4($a4) {}

class x {
    function foo3(C  $a3) : static {}
}
?>