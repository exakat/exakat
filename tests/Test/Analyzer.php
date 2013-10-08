<?php

namespace Test;

class Analyzer extends \PHPUnit_Framework_TestCase {
    function setUp() {
        shell_exec("cd ../; php bin/delete -all");
    }

    function tearDown() {
        // empty
    }
    
    function generic_test($file) {
        list($analyzer, $number) = explode('.', $file);
        
        $shell = 'cd ..; php bin/load -f tests/source/'.$file.'.php; php bin/tokenizer; php bin/analyze;';
        $res = shell_exec($shell);
        $shell = 'cd ..; php bin/export_analyzer '.$analyzer;
        $res = shell_exec($shell);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        
        $this->assertEquals($exp, $res);
    }
    
    
}

?>