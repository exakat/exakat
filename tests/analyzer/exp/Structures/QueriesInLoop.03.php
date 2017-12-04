<?php

$expected     = array('foreach($cities as $name) { /**/ } ',
                     );

$expected_not = array('while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { /**/ } ',
                     );

?>