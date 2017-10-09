<?php

$expected     = array('preg_match("/\d{4}-\d{2}-\d{2}/", $birthday)',
                     );

$expected_not = array('preg_match("/\d{4}-\d{2}-\d{3}/$", $birthday)',
                      'preg_match("^/\d{4}-\d{2}-\d{4}/$", $birthday)',
                      'preg_match("^/\d{4}-\d{2}-\d{5}/", $birthday)',
                     );

?>