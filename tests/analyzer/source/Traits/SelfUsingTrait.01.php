<?php

trait a {}

trait b { use b; }

trait c { use c, c;}

trait d { use d, d, d;}

class x {
    use a, b, c, d;
}

new x;
?>