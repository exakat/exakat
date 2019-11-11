<?php

define('d', strtolower(c));
define('e', $c->strtolower(c));
define('f', F::strtolower(c));

define('d2', 1 * strtolower(c));
define('e2', 1 * $c->strtolower(c));
define('f2', 1 * F::strtolower(c));

define('d2', d);
define('d3', \d);

?>