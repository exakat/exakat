<?php
    $MSGKEY = 519051;

    $msg_id = msg_get_queue ($MSGKEY, 0600);

    if (!msg_send ($msg_id, 1, 'Hi', true, true, $msg_err))
        echo "Msg not sent because $msg_err\n";

    $msg_id = msg_fill_queue ($MSGKEY, 0600);
?>