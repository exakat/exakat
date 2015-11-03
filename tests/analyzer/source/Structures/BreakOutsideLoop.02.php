<?php

switch ($a) {
    case 1 : 
    continue 1;
}

do {
    continue 1;
} while (1);

while(2) { continue 1; }

foreach($a as $b) continue 1;

for($i = 1; $i < 10; $i++) {
    continue 1;
}

function f($x) {
    continue 1;
}

function f2($x) {
    continue ;
}

function f3($x) {
    while(1) {
        while(2) {
            continue 2;
        }
    }
}

function f31($x) {
    while(3) {
        while(4) {
            continue 1; // only continue one
        }
    }
}

function f32($x) {
    while(3) {
        while(4) {
            continue 3; // Too many continues
        }
    }
}

?>