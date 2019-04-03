<?php

$expected     = array('assert(<<<HEREDOC
heredoc
HEREDOC)',
                      'assert("is_int(\\$int3) /* $int parameter must be an int, not just numeric */")',
                      'assert(\'is_int($int2) \' . \'/* $int parameter must be an int, not just numeric */\')',
                      'assert(\'is_int($int1) /* $int parameter must be an int, not just numeric */\')',
                     );

$expected_not = array('assert(function ($x) { return is_int($int); /* $int parameter must be an int, not just numeric */})',
                      'assert(function ($x) { return is_int($int); /* $int parameter must be an int, not just numeric */}, \'a\')',
                      'assert(function ($x) { /**/ } , \'a\')',
                      'assert(1)',
                     );

?>