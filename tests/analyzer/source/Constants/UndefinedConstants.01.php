<?php

namespace A {
    const B = 1;

    class E {
        const F = 3;
    }
}

namespace C {
    const D = 2;
}

namespace {
    print \A\B. ' '.\C\D.' '.B.' '.\C\B.' '.\E."\n";
}

?>