<?php

interface i{}

abstract class ac {}
abstract class ac2 {}

class x implements i, ac {}
class x2 implements ac, i {}
class y extends ac {}
class z extends ac implements i {}
class z2 extends ac implements ac2 {}
class a implements ac, ac2 {}

?>