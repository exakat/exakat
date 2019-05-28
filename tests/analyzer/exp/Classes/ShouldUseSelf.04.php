<?php

$expected     = array('\\x::methodx( )',
                      'x::methodx( )',
                      '\\y::methody( )',
                      'y::methody( )',
                      '\\z::methodz( )',
                      'z::methodz( )',
                     );

$expected_not = array('b::method( )',
                      '\\b::method( )',
                     );

?>