<?php

class A extends B implements C,D { }

class A2 extends B2 implements A2\C, A3\A4\A5\D { 
    const A2_UNDEFINED = A22\C;
    const A23_UNDEFINED = A223;
}

?>