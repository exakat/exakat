<?php

class x {
    protected x $b;
    protected x $b2;

    // This is recursive!
    function bar3($a) {
        if ($a > 1) {
            // Not oneself
            $this->b->bar3($a);
        }
    }

    // This is not obviously recursive!
    function bar4($a) {
        if ($a > 1) {
            // Not oneself
            $this->b2->bar3($a);
        }
    }
}
?>