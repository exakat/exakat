<?php

$expected     = array('preg_replace(\'/\\s*[\\n\\r\\f]+\\s*(\\/\\*\' . $token_tring . \')/Se\', self::NL . \'$1\', $css)',
                      'preg_replace(\'/\\/\\*\' . $this->str_slice($placeholder, 1, -1) . \'\\*\\//e\', \'\', $css, 1)',
                      'preg_replace("\'(?<!(?<![a-zA-Z0-9_\\$])" . implode(\')(?<!(?<![a-zA-Z0-9_\\$])\', $r1) . \') (?!(\' . implode(\'|\', $r2) . ")(?![a-zA-Z0-9_\\$]))\'e", "\\n", $f)',
                      'preg_replace("/$seppattern+\\/|\\/$seppattern+/meS", "/", $output)',
                     );

$expected_not = array('preg_replace(\'/\\s*[\\n\\r\\f]+\\s*(\\/\\*\' . $token_tring . \')/S\', self::NL . \'$1\', $css)',
                      'preg_replace(\'/\\/\\*\' . $this->str_slice($placeholder, 1, -1) . \'\\*\\//\', \'\', $css, 1)',
                      'preg_replace("\'(?<!(?<![a-zA-Z0-9_\\$])" . implode(\')(?<!(?<![a-zA-Z0-9_\\$])\', $r1) . \') (?!(\' . implode(\'|\', $r2) . ")(?![a-zA-Z0-9_\\$]))\'", "\\n", $f)',
                      'preg_replace("/$seppattern+\\/|\\/$seppattern+/", "/", $output)',
                     );

?>