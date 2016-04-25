<?php
/**
 * Simple example of extending the SQLite3 class and changing the __construct
 * parameters, then using the open method to initialize the DB.
 */
class a 
{
    private function c1() { }
    private function c2() { }
    protected function c3() { }
    
    function d() {
        $a = 'c1';
        $this->$a();
        $a = 'c3';
        $this->$a();
    }
}
