<?php

namespace {
    // 
split(':', 'global namespace');
explode(':', 'global namespace');

dl('global namespace');
}

namespace B {
    // will fall back to PHP 
spliti(':', 'namespace B but fallback');
eregi(':', 'namespace B but fallback');

}

namespace C {
    // will not fall back to PHP 
    SPLITI(':', 'Namespace C');
    call_user_method_array(':', 'Namespace C but fallback');

    function spliti($a, $b) {}
}

?>