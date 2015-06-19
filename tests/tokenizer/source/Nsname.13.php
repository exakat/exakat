<?php

namespace A\B {}
namespace A\B\C {}
namespace A\B\C\D {}
namespace A\B\C\D\E {}

namespace {
    C\D\E();
    X\A();
    \G();
    Y\B\C();
    Z\D\E\F();

    C\D\E::K;
    \C\D\E::K;

    C2\D\E::$k;
    \C2\D\E::$k;

    C3\D\E::l();
    \C3\D\E::l();
}

?>