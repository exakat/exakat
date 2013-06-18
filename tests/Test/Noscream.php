<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Noscream extends Tokenizeur {
    /* 3 methods */

    public function testNoscream01()  { $this->generic_test('Noscream.01'); }
    public function testNoscream02()  { $this->generic_test('Noscream.02'); }
    public function testNoscream03()  { $this->generic_test('Noscream.03'); }
}
?>