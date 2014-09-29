<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Void extends Tokenizer {
    /* 2 methods */

    public function testVoid01()  { $this->generic_test('Void.01'); }
    public function testVoid02()  { $this->generic_test('Void.02'); }
}
?>