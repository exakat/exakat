<?php

$expected     = array('preg_match_all("a" . LD . "if {$b}_page" . RD . "(.+?)" . LD . \'\\/\' . "if" . RD . "as", $c, $y)',
                     );

$expected_not = array('preg_match_all("/" . LD . "if {$b}_page" . RD . "(.+?)" . LD . \'\\/\' . "if" . RD . "/s", $c, $y)',
                     );

?>