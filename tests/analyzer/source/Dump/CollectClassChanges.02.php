<?php

class a {
    private const Ca1 = 1;
    private const Ca2 = 2;
}

class b extends a {
    private const Cb1 = 3;
    private const Cb2 = 4;
}

class c extends b {
    protected const Ca1 = 1;
    public const Cb1 = 3;

    private const Ca2 = 2;
    private const Cb2 = 4;

    const Cc = 1;
}

?>