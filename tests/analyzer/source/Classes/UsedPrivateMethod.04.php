<?php

class x {
    private function used() {}
    private function unused() {}
    
    private function foo() {
        $class::used(1);
        $class::unused(1);
        $this::used(1);
    }
}
?>