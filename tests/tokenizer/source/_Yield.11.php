<?php


    function b()
    {
        $data = 'A' . yield 3 + 4;
        $data = 'A' . yield;
        $data = 'A' . yield from ['a', foo(3)];
    }
    
    foreach(b() as $b) {
        var_dump($b);    
    }
