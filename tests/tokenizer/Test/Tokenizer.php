<?php

namespace Test;

class Tokenizer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
/*
        $ini = parse_ini_file('../../projects/test/config.ini');
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = 'Tokenizer'.str_replace('_', '\\', substr(get_class($this), 4));

        $analyzerobject = new $test_config(null);
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        // initialize Config (needed by phpexec)
        \Config::factory(array('foo', '-p', 'test'));
        
        $Php = new \Phpexec($phpversion);
        if (!$analyzerobject->checkPhpConfiguration($Php)) {
            $message = array();
            $confs = $analyzerobject->getPhpConfiguration();
            if (is_array($confs)) {
                foreach($confs as $name => $value) {
                    $confs[] = "$name => $value";
                }
                $confs = join(', ', $confs);
            }
            
            $this->markTestSkipped('Needs configuration : '.$confs.'.');
        }
*/
        $shell = 'cd ../..; php exakat cleandb; php exakat load -f ./tests/tokenizer/source/'.$file.'.php -p test; php exakat build_root -p test; php exakat tokenizer -p test;';
        $res = shell_exec($shell);
        
        $shell = 'cd ../..; php exakat export -p test -format text ';
        $res = shell_exec($shell);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        $this->assertNotContains("Label : NEXT", $exp);
        $this->assertNotContains("Parse error", $exp);
        
        $this->assertEquals($exp, $res);

        $shell = 'cd ../..; php exakat stat -json';
        $decode = json_decode(shell_exec($shell));
        
        $this->assertEquals($decode->INDEXED_count, 0, 'There are '.$decode->INDEXED_count.' INDEXED left');
    }
}

?>