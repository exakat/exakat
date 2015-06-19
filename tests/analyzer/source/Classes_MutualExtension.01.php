<?php
class a extends b {}
class b extends a {}

class c {}
class d extends c {}

class e extends f {}
class f extends g {}
class g extends e {}

class e2 extends \f2 {}
class f2 extends \g2 {}
class g2 extends \e2 {}
?>