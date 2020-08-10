<?php

class a {
    const Ca1 = 1;
    const Ca2 = 2;
}

class b extends a {
    const Cb1 = 3;
    const Cb2 = 4;
}

class c extends b {
    const Ca1 = 0;
    const Cb1 = 0;

    const Ca2 = 2;
    const Cb2 = 4;

    const Cc = 1;
}

?>