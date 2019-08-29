<?php
interface X {
  function m(Y $z): X;
}
interface Y extends X {
  // not permitted but type-safe
  function m(X $z): Y;
}

interface Y2 extends X {
  // not permitted but type-safe
  function m(X $z): A;
}

?>