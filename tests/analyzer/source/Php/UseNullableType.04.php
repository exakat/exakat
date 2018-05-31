<?php

interface i {
    function foo(?string $x);
    
    function foo2(?string $x, ?int $y);
    
    function foo3(string $x, int $y) : ?array;
    
    function foo4(string $x, ?callable $y) : ?array;
    
    function foo5(?string $x, ?callable $y) : ?array;
    
    function bar(string $x);
    
    function bar2(string $x, int $y);
    
    function bar3(string $x, int $y) : array;
    
    function bar4(string $x, callable $y) : array;
    
    function bar5(string $x, callable $y) : array;
}
?>