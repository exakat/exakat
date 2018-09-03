<?php

$expected     = array('msg_get_queue($MSGKEY, 0600)',
                      'msg_send($msg_id, 1, \'Hi\', true, true, $msg_err)',
                     );

$expected_not = array('msg_fill_queue($MSGKEY, 0600)',
                     );

?>