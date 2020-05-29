<?php

    function a() {
        assert(func_num_args() === 1, 'Wrong number of argument for ' . __METHOD__);
        ++$a;
    }

    function ba() {
        assert(1, 'Wrong number of argument for ' . __METHOD__);
        ++$ba;
    }

    function ba2() {
        assert(0, 'Wrong number of argument for ' . __METHOD__);
        ++$ba2;
    }

    function bac() {
        assert($c->methodCall(), 'Wrong number of argument for ' . __METHOD__);
        ++$bac;
    }

?>