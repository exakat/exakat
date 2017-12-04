<?php

$expected     = array('parse_str($a)',
                      'parse_str($ax)',
                      'mb_parse_str($a)',
                     );

$expected_not = array('mb_parse_str($ax)',
                     );

?>