<?php

class x {
    function foo3(C  $a3) : static { $a++; }
}

class y {
    function foo3(C  $a3) : self { $a++; }
}

class z extends y {
    function foo3(C  $a3) : parent { $a++; }
}

?>