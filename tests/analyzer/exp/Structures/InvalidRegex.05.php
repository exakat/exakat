<?php

$expected     = array('preg_replace(\'\\\'(a\\\\)\\\'si\', \'A\', $c)',
                      'preg_replace("\\\\(a)\\\\si", \'D\', $c)',
                      'preg_replace(\'\\"(a)\\"si\', \'B\', $c)',
                     );

$expected_not = array('echo preg_replace("\\"(a)\\"si", \'C\', $c)',
                      'echo preg_replace(\'\\\'(a)\\\'si\', \'A\', $c)',
                     );

?>