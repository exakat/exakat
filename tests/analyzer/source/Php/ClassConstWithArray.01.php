<?php

class x {
    private $array = array(1,2,3);
    
    const isArrayC = array(4,5,6);
    const isArray2C = [4,5,6];
    const isNotArrayC = 4;
}

interface x {
    const isArrayI = array(4,5,6);
    const isArray2I = [4,5,6];
    const isNotArrayI = 4;
}

abstract class x2 {
    private $array2 = [1,2,3];
    
    const isArrayAC = array(4,5,6);
    const isArray2AC = [4,5,6];
    const isNotArrayAC = 4;
}

?>