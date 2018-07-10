<?php

$expected     = array('msgpack_serialize($variable)',
                      'msgpack_unserialize($serialized)',
                     );

$expected_not = array('msgpack_deserialize($serialized)',
                     );

?>