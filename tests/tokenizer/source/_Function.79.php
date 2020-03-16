<?php

function foo() : static {};

$b = function () : static {};
$c = function () use ($b): static {};

class x {
    function bar() : static {
        return $a = fn(): static => 1;
    }

}
?>