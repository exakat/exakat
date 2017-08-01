<?php

$expected     = array('"Content-Type: " . $x . " application/octet-stream"',
                      '"Max-Forwards: " . " 34"',
                      );

$expected_not = array("Transfer-Encoding \$z UTF-8", 
                      "'normal ' . \$y . 'string'",
                      );

?>