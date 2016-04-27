<?php

wp_create_nonce('a', $b);

// Not to be found
wp_create_nonce($a, $b);

// Not to be found
$a->wp_create_nonce("b", $b);

?>