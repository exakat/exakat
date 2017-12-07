<?php

$expected     = array('preg_match("/asdf$a" . $d . "/isw", $c, $b)',
                     );

$expected_not = array('preg_match("{$a}asdf" . $d . "/is", $c, $b)',
                     );

?>