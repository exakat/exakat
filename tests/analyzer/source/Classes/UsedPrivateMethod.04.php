<?php

class x {
    private function usedSelf() {}
    private function usedClass() {}
    private function usedThis() {}
    private function unused() {}
    
    private function foo() {
        $class::usedClass(1);
        $class::unused(1);
        $this::usedThis(1);
        self::usedSelf(1);
    }
}
?>