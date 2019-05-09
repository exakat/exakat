<?php

namespace {
    function foo_global() { echo __METHOD__;}

    foo_global();
    foo_in_abc();

}

namespace A\B\C {
    function foo_in_abc() { echo __METHOD__;}

    foo_global();
    foo_in_abc();
}

namespace D\E\F {
    foo_global();
    foo_in_abc();
}

?>