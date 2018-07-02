<?php

$expected     = array('eio_nop(EIO_PRI_DEFAULT, "my_nop_cb", NULL)',
                      'eio_cancel($req)',
                      'eio_nop(EIO_PRI_DEFAULT, "my_nop_cb", NULL)',
                      'eio_event_loop( )',
                     );

$expected_not = array('eio_event_loop(2)',
                     );

?>