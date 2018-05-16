<?php

class X { use tx; }
new X();
trait tx { public function __construct() {echo __METHOD__;}}

new Y();
class Y { use ty; }
trait ty{ private function __construct() {}}

class Z {  use tz; }
trait tz{ protected function __construct() {echo __METHOD__;}}
new Z();

?>