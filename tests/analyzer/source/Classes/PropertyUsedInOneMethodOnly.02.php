<?php

class foo {
    private $once = 1;
    const ONCE = 1;
    private $counter = 0;
    private $counter2 = 1;
    private $counter3 = 1;
    
    function bar() {
        // $this->once is never used anywhere else. 
        someFunction($this->once);
        someFunction(self::ONCE);   // Make clear that it is a 
    }

    function bar2() {
        static $localCounter = 0;
        $this->counter++;
        
        // $this->once is only used here, for distinguising calls to someFunction2
        if ($this->counter > 10) { // $this->counter is used only in bar2, but it may be used several times
            return false;
        }
        someFunction2($this->counter);

        // $localCounter keeps track for all the calls
        if ($localCounter > 10) { 
            return false;
        }
        someFunction2($localCounter);
        
        $this->counter2++;
        $this->counter3++;
    }

    function bar3() {
        // counter2 is used in 2 distinct methods
        $this->counter2++;
        $this->counter3++;
    }

    function bar4() {
        // counter3 is used in 3 distinct methods
        $this->counter3++;
    }
}

?>