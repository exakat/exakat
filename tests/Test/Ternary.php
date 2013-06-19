<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ternary extends Tokenizeur {
    /* 3 methods */

    public function testTernary01()  { $this->generic_test('Ternary.01'); }
    public function testTernary02()  { $this->generic_test('Ternary.02'); }
    public function testTernary03()  { $this->generic_test('Ternary.03'); }
}
?>