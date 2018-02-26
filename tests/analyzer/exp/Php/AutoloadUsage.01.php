<?php

$expected     = array('spl_autoload_register(function ($d) { /**/ } )',
                     );

$expected_not = array('spl_autoload_register(\'nope\')',
                     );

?>