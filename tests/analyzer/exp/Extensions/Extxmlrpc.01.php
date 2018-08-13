<?php

$expected     = array('xmlrpc_encode_request("method", array(1, 2, 3))',
                      'xmlrpc_decode($file)',
                      'xmlrpc_is_fault($response)',
                     );

$expected_not = array('xmlrpc_was_fault($response)',
                     );

?>