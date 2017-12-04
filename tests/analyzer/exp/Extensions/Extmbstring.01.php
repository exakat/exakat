<?php

$expected     = array('mb_split("\\s", "hello world")',
                      'mb_split("/\\s/", "hello world")',
                     );

$expected_not = array('mb_strtolower("/\\s/")',
                     );

?>