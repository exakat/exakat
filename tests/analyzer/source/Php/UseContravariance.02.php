<?php
interface X {
  function m(Y $zz);
}

interface Y extends X {
  function m(X $z);
}

interface Y2 extends X {
  function m(A $z);
}

interface Y3 extends X {
  function m(Y $z3);
}

?>