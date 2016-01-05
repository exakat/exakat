<?php

// OK (3 else if)
if (1) {

} else if (2) {

} else if (3) {

} else {

}

// KO (else is a sequence)
if (11) {

} else { 
    if (12) {

    } else if (13) {

    } else {

    }
    
    $a++;
}

// KO (else is a sequence)
if (21) {

} else { 
    $a++;

    // OK (else not a sequence)
    if (22) {

    } else if (23) {

    } else if (33) {

    } else {

    }
}

// OK (3 else if)
if (31) {

} else { 
    if (32) {

    } else if (33) {

    } else {

    }
}

?>