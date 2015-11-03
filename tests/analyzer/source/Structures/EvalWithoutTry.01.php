<?php

eval ('$x = 1;');

try {
    eval ('$x = 2;');
} catch(Throwable $e) {
    
}

?>