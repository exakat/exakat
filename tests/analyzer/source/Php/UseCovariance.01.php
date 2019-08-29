<?php
class X {
  function m(Y $z): X {}
}

class Y extends X {
  // not permitted but type-safe
  function m(X $z): Y {}
}

class Y2 extends X {
  // not permitted but type-safe
  function m(X $z): A {}
}

?>