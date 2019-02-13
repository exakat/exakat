<?php

namespace A\B\C;


class D
{
    protected $e = [];

    public function __construct(array $fArg)
    {
        $this->e = $fArg;
        $this->e = $fUnique;
    }

    public function b(array $fArg)
    {
        $useArg = 1;
        $a = function () use ($useArg) {
            $this->e = $useArg;
            $this->e = $useUnique;
        };
    }

}

?>