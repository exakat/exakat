<?php

$expected     = array('preg_replace(\'#\' . $config->projects_root . \'/projects/.*?/code#ies\', \'\', $files)',
                      'preg_replace(\'#.*projects/.*?/code/#e\', \'/\', $filename)',
                      'preg_replace(\'#.*projects/.*?/code/#sme\', \'/\', $filename)',
                      'preg_replace(\'/\' . $name . \'/ise\', $gremlinArray, $query)',
                     );

$expected_not = array('preg_replaced(\'/1sdf/\', \'4\', $r)',
                      'preg_replace(\'/(PHP Warning|Warning|Strict Standards|PHP Warning|asfd)/\', \'4\', $r)',
                     );

?>