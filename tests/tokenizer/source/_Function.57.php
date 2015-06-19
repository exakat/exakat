<?php

    function B() {
        C(array(__CLASS__, 'D'));
    }

    function B1() {
        print(array(__CLASS__, 'D'));
    }

    function B2() {
        C(array(__CLASS__, 'D'));
        print array(__CLASS__, 'D');
    }
