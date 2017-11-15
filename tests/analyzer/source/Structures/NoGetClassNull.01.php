<?php

class A {
  public function f($x) {
    var_dump($x());
    var_dump(get_class(null));
    var_dump(get_class());
    var_dump(get_class($x));
    var_dump(get_class(1, $s));
  }
}

$a = new A();
$a->f('get_class');

?>