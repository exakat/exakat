<?php

// OK (3 else if)
if ($a === 1) {

} else if ($a === 2) {

} else if ($a === 3) {

} else {

}

// KO (else is a sequence)
if ($a === 11) {

} else { 
    if ($a === 12) {

    } else if ($a === 13) {

    } else {

    }
    
    $a++;
}

// KO (else is a sequence)
if ($a === 21) {

} else { 
    $a++;

    // OK (else not a sequence)
    if ($a === 22) {

    } else if ($a === 23) {

    } else if ($a === 33) {

    } else {

    }
}

// OK (3 else if)
if ($a === 31) {

} else { 
    if ($a === 32) {

    } else if ($a === 33) {

    } else {

    }
}

?>