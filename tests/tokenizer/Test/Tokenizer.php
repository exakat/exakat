<?php

namespace Test;

class Tokenizer extends \PHPUnit_Framework_TestCase {
    public function setUp() {
        shell_exec("cd ../..; php bin/delete -all");
    }

    public function generic_test($file) {
        $shell = 'cd ../..; php bin/load -f ./tests/tokenizer/source/'.$file.'.php; php bin/build_root -V; php bin/tokenizer -p test; php bin/export -text -f ./tests/tokenizer/source/'.$file.'.php';
        $res = shell_exec($shell);
        
        $exp = file_get_contents('exp/'.$file.'.txt');
        $this->assertNotContains("Label : NEXT", $exp);
        $this->assertNotContains("Parse error", $exp);
        
        $this->assertEquals($exp, $res);

        $shell = 'cd ../..; php bin/stat -json';
        $decode = json_decode(shell_exec($shell));
        
        $this->assertEquals($decode->INDEXED_count, 0, 'There are '.$decode->INDEXED_count.' INDEXED left');
    }
}

?>