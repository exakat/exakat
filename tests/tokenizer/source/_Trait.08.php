<?php

namespace A;

trait t2 {}

trait t {
    use namespace\t2;
}

?>