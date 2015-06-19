<?php

class A1 extends B implements C, D { }
class A2 extends B implements C, D, E { }
class A3 implements C, D, E, F { }
class A4 implements \C, \D, \E, \F { }