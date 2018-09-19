<?php

use usedT as use_usedT;
use unusedT2 as unusedT4;
use unusedT2;

trait usedT { }

trait unusedT {}
trait unusedT2 {}
trait unusedT3 {}

class usingT {
    use use_usedT;
}
?>