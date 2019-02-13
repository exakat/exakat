<?php

class X {}

class Y extends X {
  public function do(self $a, parent $b) {
      echo '👍';
  }   
}

$x = new X;
$y = new Y;

$y->do($y, $x);