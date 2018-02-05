<?php

$expected     = array('wddx_packet_start("SOME DATA ARRAY")',
                      'wddx_add_vars($packet, $key)',
                      'wddx_packet_end($packet)',
                     );

$expected_not = array('someClass::wddx_packet_end( )',
                      'wddx_packet_end( )',
                     );

?>