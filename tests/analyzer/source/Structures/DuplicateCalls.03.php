<?php
function foo($name, $last) {
    // The name decoration on the string is done twice. Once should be cached in a variable.
    echo "Hello, ".ucfirst(strtolower($name)).' '.ucfirst(strtolower($last))."<br />";
    
    $query = 'Insert into visitors values ("'.ucfirst(strtolower($name)).'")';
    $res = $db->query($query);
}

?>