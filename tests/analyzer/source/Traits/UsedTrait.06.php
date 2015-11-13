<?php

trait t1 {
    use t2;
}

trait t2 {
    use t3, t1;
}

trait t3 {}

trait unused {}
?>