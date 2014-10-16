<?php

$expected     = array("define('a', 1)",
                      "define('b', a + 2)",
                      "define('c', b + a)",
                      "define('d', strtolower(c) + a)"
);

$expected_not = array("define('e', 1 + $a)");

?>