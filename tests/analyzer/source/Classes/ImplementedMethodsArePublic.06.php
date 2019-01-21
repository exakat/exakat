<?php

class x { 
    function methodFromX() {}
}

class y extends x implements i, j { 
    protected function methodFromX() {}
    protected function methodFromI() {}
    protected function methodFromJ() {}
    protected function methodFromK() {}
}

interface i {
    function methodFromI();
}

interface j extends k {
    function methodFromJ();
}

interface k {
    function methodFromK();
}
?>