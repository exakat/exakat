<?php

namespace A {
    $used_once_in_a++;
    $used_once_in_a_and_b++;
}

namespace B {
    $used_once_b++;
    $used_once_in_a_and_b++;
}
?>