<?php

const A = 'some path as constant';
const BA = 'not some path as constant';

class A {
    const A = 'some path as class constant';
}

fopen(A);
fopen(A::A);

?>