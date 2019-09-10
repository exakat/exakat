<?php

function foo1() {
    if (1) {
        10;
    } else {
        1;
    }
}

function foo2() {
    if (1) {
        if (2) {
            20;
        } else {
            2;
        }
    } else {
        1;
    }
}


function foo3() {
    if (1) {
        if (2) {
            if (3) {
                30;
            } else {
                3;
            }
        } else {
            2;
        }
    } else {
        1;
    }
}

function foo4() {
    if (1) {
        if (2) {
            if (3) {
                if (4) {
                    40;
                } else {
                    4;
                }
            } else {
                3;
            }
        } else {
            2;
        }
    } else {
        1;
    }
}

function foo5() {
    if (1) {
        if (2) {
            if (3) {
                if (4) {
                    if (5) {
                        51;
                    } else {
                        5;
                    }
                } else {
                    4;
                }
            } else {
                3;
            }
        } else {
            2;
        }
    } else {
        1;
    }
}

?>