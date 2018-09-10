<?php

$expected     = array('gnupg_init( )',
                      'gnupg_setsignmode($res, GNUPG_SIG_MODE_CLEAR)',
                      'gnupg_addsignkey($res, "8660281B6051D071D94B5B230549F9DC851566DC", "test")',
                      'gnupg_sign($res, "just a test")',
                      'GNUPG_SIG_MODE_CLEAR',
                     );

$expected_not = array('gnupg_logout( )',
                     );

?>