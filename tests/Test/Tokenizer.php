<?php

namespace Test;

class Tokenizer extends \PHPUnit_Framework_TestCase {
    function setUp() {
        shell_exec("cd ../; php bin/delete -all");
    }

    function tearDown() {
        // empty
    }
    
    function generic_test($file) {
        $shell = 'cd ..; php bin/load -f tests/source/'.$file.'.php; php bin/tokenizer; php bin/export -text -f '.dirname(__DIR__).'/source/'.$file.'.php';
        $res = shell_exec($shell);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        $this->assertNotContains("Label : NEXT", $exp);
        $this->assertNotContains("Parse error", $exp);
        
        $this->assertEquals($exp, $res);

        $shell = 'cd ..; php bin/stat -json';
        $decode = json_decode(shell_exec($shell));
        
        $this->assertEquals($decode->INDEXED_count, 0, 'There are '.$decode->INDEXED_count.' INDEXED left');
    }
    
    
}

?>