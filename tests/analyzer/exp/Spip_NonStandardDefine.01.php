<?php

$expected     = array("define('NO_INITIAL_UNDERSCORE_AND_lowercase', 3)",
                      "define('_NO_ALL_uppercase', 2)",
                      "define('NO_INITIAL_UNDERSCORE', 1)",
);

$expected_not = array("define('_ALL_UPPERCASE_AND_1', 4)",
                      "define(\$dynamic, 5)",
);

?>