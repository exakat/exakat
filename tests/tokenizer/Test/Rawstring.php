<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Rawstring extends Tokenizer {
    /* 4 methods */

    public function testRawstring01()  { $this->generic_test('Rawstring.01'); }
    public function testRawstring02()  { $this->generic_test('Rawstring.02'); }
    public function testRawstring03()  { $this->generic_test('Rawstring.03'); }
    public function testRawstring04()  { $this->generic_test('Rawstring.04'); }
}
?>