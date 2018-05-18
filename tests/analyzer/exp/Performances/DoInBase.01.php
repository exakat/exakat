<?php

$expected     = array('while ($row = sqlsrv_fetch_object( )) { /**/ } ',
                      'while ($row = sqlsrv_fetch_array( )) { /**/ } ',
                      'while ($row = $res->fetchArray( )) { /**/ } ',
                     );

$expected_not = array('while ($row2 = sqlsrv_fetch_object( )) { /**/ } ',
                      'while ($row = sqlsrv_fetch_field( )) { /**/ } ',
                     );

?>