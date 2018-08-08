<?php

// extended, not instantiated
class x {}
class y1 extends x {}
class y2 extends x {}

// extended, instantiated
class x2 {}
class y21 extends x2 {}
class y22 extends x2 {}
new x2();

// extended, not instantiated, abstract
abstract class x3 {}
class y31 extends x3 {}
class y32 extends x3 {}

?>