<?php

namespace Test;

class Tokenizer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
        $config = \Config::factory();

        $shell = 'cd ../..; '.$config->php.' exakat cleandb; '.$config->php.' exakat load -f ./tests/tokenizer/source/'.$file.'.php -p test; '.$config->php.' exakat build_root -p test; '.$config->php.' exakat tokenizer -p test;';
        $res = shell_exec($shell);
        
        $shell = 'cd ../..; '.$config->php.' exakat export -p test -format text ';
        $res = shell_exec($shell);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        $this->assertNotContains('Label : NEXT', $exp);
        $this->assertNotContains('Parse error', $exp);
        
        $this->assertEquals($exp, $res);

        $shell = 'cd ../..; '.$config->php.' exakat stat -json';
        $decode = json_decode(shell_exec($shell));
        
        $this->assertEquals($decode->INDEXED_count, 0, 'There are '.$decode->INDEXED_count.' INDEXED left');
    }
}

?>