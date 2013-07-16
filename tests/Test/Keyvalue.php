<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Keyvalue extends Tokenizeur {
    /* 3 methods */

    public function testKeyvalue01()  { $this->generic_test('Keyvalue.01'); }
    public function testKeyvalue02()  { $this->generic_test('Keyvalue.02'); }
    public function testKeyvalue03()  { $this->generic_test('Keyvalue.03'); }
}
?>