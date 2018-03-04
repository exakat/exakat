<?php
//OK, int
if($a == 'a') { 
    return (int) $a;
}

if($a == 'b') { 
    return (array) $a;
}

if($a == 'c') { 
    return (object) $a;
}

// OK, real
if($a == 'd') { 
    return (real) $a;
}

if($a == 'e') { 
    (unset) $a;
}

if($a == 'f') { 
    return (string) $a;
}

?>