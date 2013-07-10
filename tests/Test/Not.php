<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Not extends Tokenizeur {
    /* 3 methods */
    public function testNot01()  { $this->generic_test('Not.01'); }
    public function testNot02()  { $this->generic_test('Not.02'); }
    public function testNot03()  { $this->generic_test('Not.03'); }
}
?>