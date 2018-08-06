<?php

$expected     = array('@imap_open($server, $user, $pass)',
                      'imap_fetchbody($mbox, $msgno, $part)',
                      'imap_msgno($mbox, $mess)',
                      'imap_fetchstructure($mbox, $msgno)',
                     );

$expected_not = array(
                     );

?>