<?php

define('used', 1);
define('unused', 1);
define('used_with_false', 1, false);
define('used_but_case_insensitive', 2);

define('USED_TOO', 2, true);
define('USED_TOO_IN_LOWERCASE', 2, true);

print used + used_with_false + USED_TOO + USED_BUT_CASE_INSENSITIVE + used_too_in_lowercase;

?>