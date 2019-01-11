<?php

// OK
round($value, $precision, wc_get_tax_rounding_mode( ));
round($value, $precision, function ( ) {});

round($value, $precision, 3 + 3);

round($value, $precision, (PHP_ROUND_HALF_UP));
round($value, $precision, ($a ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP));

?>