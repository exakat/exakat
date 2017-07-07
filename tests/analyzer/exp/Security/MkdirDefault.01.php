<?php

$expected     = array('mkdir(\'/path/to/dir\')');

$expected_not = array('mkdir(\'/path/to/dir2\', 0777)',
                      'mkdir(\'/path/to/dir3\', 0770)',
                      );

?>