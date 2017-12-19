<?php

interface i {}
interface i2 {}
interface i3 {}
interface i4 {}
interface i5 {}

function foo():i {}
function ():i5 {};

class y {
    function foo():i2 {}
}

interface j {
    function foo():i3;
}

trait t {
    function foo():i4 {}
}



?>