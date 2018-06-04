<?php

json_decode($naked); 

echo json_decode($straight); 

try {
    json_decode($inTry); 
} catch(Exception $e) {

}

$a = json_encode($withCall); 
if (json_last_error() !== '') {
    die('ERROR');
}

$a = json_encode($withCall2); 
switch (\json_last_error_msg()) {
    case true: 
    die('ERROR');
}



?>