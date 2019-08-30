<?php
class X {
  function m(Y $zz) {}
}

class Y extends X {
  function m(X $z) {}
}

class Y2 extends X {
  function m(A $z) {}
}

class Y3 extends X {
  function m(Y $z3) {}
}

?>