<?php
class A {
  private $value = 1;
  public function getClosure() 
  {
    return function() { return $THIS->value; };
  }

  public function dontGetClosure() 
  {
    return $THIS->value;
  }
}

$a = new A;
$fn = $a->getClosure();
echo $fn(); // 1

?>