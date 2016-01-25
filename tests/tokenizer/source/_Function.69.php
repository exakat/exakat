<?php 

function s0() {}
function s1($a) {}
function s2($a, $b) {}
function s3($a, $b, $c) {}

function () {};
function ($a2) {};
function ($a2, $b2) {};
function ($a2, $b2, $c2) {};

list(, $a) = [1,2];
list($b, $a) = [1,2];
list($b,) = [1,2];

list($a, $b, $c) = [1,2, 3];
list($a, $b,   ) = [1,2, 3];
list($a,   , $c) = [1,2, 3];
list($a,   ,   ) = [1,2, 3];
list(  , $b, $c) = [1,2, 3];
list(  , $b,   ) = [1,2, 3];
list(  ,   , $c) = [1,2, 3];
//list(  ,   ,   ) = [1,2, 3];



 ?>