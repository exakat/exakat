<?php

namespace A {
    class B implements Reflector {}
    class C implements \Reflector {}
}

namespace {
    class B implements Reflector {}
    class C implements \Reflector {}
}

?>