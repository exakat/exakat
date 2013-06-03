<?php

class Test_Tokenizeur extends PHPUnit_Framework_TestCase {
    function generic_test($file) {
        $shell = 'cd ..; sh clean.sh; php analyzer.php -f tests/test/'.$file.'.php; php ex2text.php';
        $res = shell_exec($shell);
        // @todo remove this hack : no need to restart neo4j for a test! 
        $res = substr($res, 289);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        
        $this->assertEquals($exp, $res);
    }
}

?>