<?php

while($row = $res->fetchArray()) { 
    $c += $row['e'];
}

while($row = sqlsrv_fetch_array()) { 
    $c += $row['e'];
}

while($row = sqlsrv_fetch_object()) { 
    $c += $row->e;
}

while($row2 = sqlsrv_fetch_object()) { 
    $c += $row->e;
}

while($row = sqlsrv_fetch_field()) { 
    $c += $row;
}

?>