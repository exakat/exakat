<?php

namespace Test;

class Tokenizeur extends \PHPUnit_Framework_TestCase {
    function generic_test($file) {
        $shell = 'cd ..; php bin/load -f tests/source/'.$file.'.php; php bin/analyzer; php bin/export -text -f tests/source/'.$file.'';
        $res = shell_exec($shell);
        // @todo remove this hack : no need to restart neo4j for a test! 
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        
        $this->assertEquals($exp, $res);
        $this->assertNotContains("Label : NEXT", $exp);
    }
}

?>