<?php

class foo {
    private $once = 1;
    private $twiceSM = 2;
    private $twiceDM = 3;
    private $threeTimes = 4;
    
    function method1() {
        echo $this->twiceSM;
        echo $this->once;
        echo $this->twiceSM;
        echo $this->twiceDM;
        echo $this->undefined;
        echo $this->undefined2;
    }

    function method2() {
        echo $this->twiceDM . $this->threeTimes . $this->threeTimes + $this->threeTimes;
        echo $this->undefined2;
    }
}
?>