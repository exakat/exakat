<?php

namespace Test;

class Tokenizer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
        print $shell = 'cd ../..; php exakat cleandb; php exakat load -f ./tests/tokenizer/source/'.$file.'.php -p test; ';
        $res = shell_exec($shell);
        
        $shell = 'cd ../..; php exakat export -p test -format text ';
        $res = shell_exec($shell);
        
        if (!file_exists('exp/'.$file.'.txt') == 0) {
            $this->assertNotContains('No exp file ', '');
        }
        
        $exp = file_get_contents('exp/'.$file.'.txt');

        $this->assertEquals($exp, $res);
    }
}

?>