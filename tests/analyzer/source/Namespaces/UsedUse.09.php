<?php

namespace {
use A\B as C;
use C\D as E;
use E\F as G;

(new G())->go();
}

//namespace A\B\D\F {
namespace E {
    class F{
        function go() { echo __CLASS__; }
    }
}
?>