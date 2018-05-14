<?php

function a(CC $a): resource {}

function b(mixed $b){}

const string = 1.0;

function c(real $c = string){
    echo $c;
}

echo string; 
echo numeric; 

c();

?>