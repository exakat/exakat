<?php

$expected     = array('wincache_ucache_get("products" . $search_terms, $success)',
                     );

$expected_not = array('WincacheException',
                     );

?>