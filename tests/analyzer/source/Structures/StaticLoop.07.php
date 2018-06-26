<?php

while ($row = $res->fetchArray(1)) { 
    echo $row[1];
}

while ($row2 = $res->fetchArray(2)) { 
    echo $row[1];
}

while ($row3 = $res->fetchArray($res)) { 
    print_r($row3);
}

?>