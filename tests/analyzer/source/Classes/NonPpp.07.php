<?php

trait t { function x() { print __METHOD__."\n";} 
          static function x2() {}
          final function x3() {}
          final public function x3p() {}
          public final function px3() {}
         }

class c { use t; }

$c = new c();
$c->x();
?>