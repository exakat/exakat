<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Integer extends Tokenizer {
    /* 5 methods */
    public function testInteger01()  { $this->generic_test('Integer.01'); }
    public function testInteger02()  { $this->generic_test('Integer.02'); }
    public function testInteger03()  { $this->generic_test('Integer.03'); }
    public function testInteger04()  { $this->generic_test('Integer.04'); }
    public function testInteger05()  { $this->generic_test('Integer.05'); }
}
?>