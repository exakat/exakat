<?php

$expected     = array('igbinary_serialize(strtolower($variable))',
                      'igbinary_unserialize($serialized)',
                     );

$expected_not = array('strtolower($variable)',
                     );

?>