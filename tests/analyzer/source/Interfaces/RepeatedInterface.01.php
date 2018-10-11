<?php

use i as j;
use k as l;

interface i {}
interface m {}

class x implements i, i, i {}

class x2 implements i, i, \i {}

class x3 implements i, a, j {}

class x4 {}

class x5 implements i, k {}

class x6 implements i, k, a, m {}

?>