<?php

//interface a {}

interface i extends a {}
interface i2 extends a, i2 {}
interface i3 extends a {}
interface i4 extends a {}
interface i5 {}

class a implements i5 {}

new a;

?>