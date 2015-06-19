<?php

class x {
    private function privatem() {}
    private function privatema() {}
    private static function privatemsself() {}
    private static function privatepmstatic() {}

    private function privateUnused() {}
    public function publicp() {}
    
    function y() {
        $this->privatem();
        $this->privatema(3);
        
        self::privatemsself();
        static::privatepmstatic();
    }
}
?>