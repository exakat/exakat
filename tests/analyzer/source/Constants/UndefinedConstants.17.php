<?php

namespace A {
    use const E_NOTICE as USE_E_NOTICE;

    echo E_NOTICE;
    echo USE_E_NOTICE;
    echo \E_NOTICE;
    echo A\E_NOTICE;
    echo A\USE_E_NOTICE;
}
?>