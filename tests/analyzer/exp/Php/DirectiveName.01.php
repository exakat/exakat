<?php

$expected     = array('ini_set(\'NotADirective\', 0)',
                      '\\ini_get(\'AlsoNotADirective\')',
                      'ini_get(\'session.auto_start\')',
                      'ini_get(\'session.use_trans_sid\')',
                      'ini_get(\'session.gc_maxlifetime\')',
                      'ini_get(\'session.cookie_lifetime\')',
                      'ini_set(\'session.use_trans_sid\', $config[\'use_trans_sid\'] ? 1 : 0)',
                      'ini_set(\'session.use_cookies\', $config[\'use_cookies\'] ? 1 : 0)',
                      'ini_get(\'session.use_cookies\')',
                      'ini_get(\'magic_quotes_sybase\')',
                      'ini_set(\'session.cookie_domain\', $config[\'domain\'])',
                      'ini_get(\'session.cookie_domain\')',
                      'ini_set(\'session.cookie_lifetime\', $config[\'expire\'])',
                      'ini_set(\'session.gc_maxlifetime\', $config[\'expire\'])',
                      'ini_set(\'session.auto_start\', 0)',
                      'ini_set("session.cookie_httponly", 1)',
                     );

$expected_not = array('ini_get($a, $b)',
                      'ini_set(CONSTANTE, 3)',
                      'ini_get("$a", $b)',
                     );

?>