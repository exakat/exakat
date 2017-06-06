<?php

interface A  { 
    function D(E $f); 
}

trait t  { 
    function D(E $f) {}
}

class c  { 
    function D(E $f) {}
}


?>