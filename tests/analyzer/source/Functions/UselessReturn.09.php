<?php

// OK
function ($a1) {
    $a++;
    return 1;
};

// Wrong
function ($a2) {
    $a++;
    return ;
};

// OK
function ($a3) {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
    return 2;
};

// KO
function ($a4) {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
    return ;
};

// OK (Return is not the last
function ($a5) {
    if ($a++) {
        return ;
    } else {
        return 1;
    }
};

// OK (No return)
function ($a6) {
    $a++;
};
?>