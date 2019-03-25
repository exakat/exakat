<?php

    const D = 3;
    define('E', 4);
    class x {
        const F = array();
    }
    const C = false;

    function trim1(string $a)
    {
        return 1;
    }

    function trim2(int $b = E::D)
    {
        return 1;
    }

    function trim3(string $f = null)
    {
        return 1;
    }

    function trim4(string $f = C ? D : null)
    {
        return 1;
    }

    function trim5(string $f = C ? \D : null)
    {
        return 1;
    }

    function trim6(string $f = C ? E : null)
    {
        return 1;
    }

    function trim7(string $f = C ? x::F : null)
    {
        var_dump($f);
    }
    
    trim7();
?>
