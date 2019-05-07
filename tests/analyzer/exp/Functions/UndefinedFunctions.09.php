<?php

$expected     = array('A\mysqli_free_result( )',
                      'A\mysqli_get_cache_stats( )',
                      'foo( )',
                     );

$expected_not = array('foo( )( )',
                      'mysqli_field_seek( )',
                      'mysqli_field_count( )',
                      'mysqli_fetch_row( )',
                      '\mysqli_field_tell',
                     );

?>