<?php

$expected     = array("glob('ko1', 1)",
                      "glob('ko2', \"1\")",
                      "glob('ko3', '1')",
                      "glob('ko4', null)",
                      "glob('ko5', FILE_APPEND | 1)",
                      "glob('ko6', \\FILE_APPEND + LOCK_EX)");

$expected_not = array();

?>