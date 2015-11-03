<?php

    //-* les define sont toutes majuscules et préfixé par _
define('NO_INITIAL_UNDERSCORE', 1);
define('_NO_ALL_uppercase', 2);
define('NO_INITIAL_UNDERSCORE_AND_lowercase', 3);

// OK
define('_ALL_UPPERCASE_AND_1', 4);

// omitted
define($dynamic, 5);

?>
