<?php

class x {
    function foo($a) {
        if ($a > 1) {
            $this->foo($a);
        }
    }

    function bar($a) {
        if ($a > 1) {
            // Not oneself
            $a->bar($a);
        }
    }

    // This is recursive!
    function bar2(x $a) {
        if ($a > 1) {
            // Not oneself
            $a->bar2($a);
        }
    }

    function foobar() {
        $this->foobar();
    }

}
?>