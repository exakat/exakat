<?php
abstract class A           { abstract function bar(stdClass $x);  }
abstract class B extends A { abstract function bar($x): stdClass; }

abstract class A2           { abstract function bar(stdClass $x);  }
abstract class B2 extends A2 { abstract function bar2($x): stdClass; }

?>