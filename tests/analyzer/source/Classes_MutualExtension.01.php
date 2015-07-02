<?php

// a <-> b
class a extends b {}
class b extends a {}

// d  -> c
class c {}
class d extends c {}

// e  -> f -> g -> e
class e extends f {}
class f extends g {}
class g extends e {}

// e2  -> f2 -> g2 -> e2
class e2 extends \f2 {}
class f2 extends \g2 {}
class g2 extends \e2 {}
?>