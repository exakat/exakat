<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class String extends Tokenizeur {
    /* 3 methods */
    public function testString01()  { $this->generic_test('String.01'); }
    public function testString02()  { $this->generic_test('String.02'); }
    public function testString03()  { $this->generic_test('String.03'); }
}
?>