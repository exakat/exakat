<?php

$expected     = array("'{__NORUNTIME__}'",
                      "'{_run_insert (.*)}'",
                      "'~_run_in(.*)~'");

$expected_not = array("'/* android */'",
                      "'###'",
                      "'{}'",
                      "'//'",
                      "'/android/iphone/i'");

?>