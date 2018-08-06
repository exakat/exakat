<?php

namespace A {
    function assert($a) {}
}

namespace B\C {
    function assert($bc) {}
}

namespace B\C {
    class x {
        function assert($x) {}
    }
}

?>