<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Integer extends Tokenizeur {
    /* 4 methods */
    public function testInteger01()  { $this->generic_test('Integer.01'); }
    public function testInteger02()  { $this->generic_test('Integer.02'); }
    public function testInteger03()  { $this->generic_test('Integer.03'); }
    public function testInteger04()  { $this->generic_test('Integer.04'); }
}
?>