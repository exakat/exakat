<?php
//Ignore, because of elseif 
if($a == 'a') { 
    $a++;
} elseif(is_numeric($a)) { 
    return 1 / (int) $a;
}

//OK, in the then
if($a == 'b') { 
    return 1 / (int) $a;
} elseif(is_numeric($a)) { 
    foo();
}

//OK, in the else
if($a == 'c') { 
    foo();
} else { 
    return (int) $a;
}

//OK, in the then
if($a == 'd') { 
    return (int) $a;
} else { 
    foo();
}

?>