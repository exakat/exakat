<?php

$expected     = array('Event',
                      'EventBufferEvent',
                      'EventUtil',
                      'EventBufferEvent',
                      'EventBufferEvent',
                      'EventBufferEvent',
                      'Event',
                      'EventBufferEvent',
                      'EventBufferEvent',
                      'EventBufferEvent($base, NULL, EventBufferEvent::OPT_CLOSE_ON_FREE | EventBufferEvent::OPT_DEFER_CALLBACKS, "readcb", NULL, "eventcb")',
                      'EventDnsBase($base, TRUE)',
                      'EventBase( )',
                     );

$expected_not = array(
                     );

?>