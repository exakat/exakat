<?php

foreach($a1 as $b ) { 
    switch ($b1) { 
        case 1 : 
        continue; 
    } 
}

foreach($a2 as $b ) { 
    switch ($b2) { 
        case 1 : 
        break; 
    } 
}

switch ($b3) { 
    case 1 : 
    foreach($a3 as $b ) { }
    continue; 
} 

foreach($a4 as $b ) { 
    switch ($b4) { 
        case 1 : 
            return function ($x) { foreach($x as $xx) { continue; }};
    } 
}

?>