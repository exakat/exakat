<?php

$expected     = array('$s( )',
                      '[\'x\', \'smx\']( )',
                      '[\\x::class, \'smx\']( )',
                     );

$expected_not = array('$s2( )',
                      '[\'x\', \'sm\']( )',
                      '[\\x::class, \'sm\']( )',
                     );

?>