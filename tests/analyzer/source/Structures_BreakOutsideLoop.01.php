<?php

switch ($a) {
    case 1 : 
    break 1;
}

do {
    break 1;
} while (1);

while(2) { break 1; }

foreach($a as $b) break 1;

for($i = 1; $i < 10; $i++) {
    break 1;
}

function f($x) {
    break 1;
}

function f2($x) {
    break ;
}

function f3($x) {
    while(1) {
        while(2) {
            break 2;
        }
    }
}

function f31($x) {
    while(3) {
        while(4) {
            break 1; // only break one
        }
    }
}

function f32($x) {
    while(3) {
        while(4) {
            break 3; // Too many breaks
        }
    }
}

?>