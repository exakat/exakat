<?php

$expected     = array('$file = fopen($project_list, "r")',
                     );

$expected_not = array('$file2 = fopen($project_list, "r")',
                      '$file3 = fopen($project_list, "r")',
                     );

?>