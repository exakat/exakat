<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Closure extends Tokenizer {
    /* 2 methods */

    public function testClosure01()  { $this->generic_test('Closure.01'); }
    public function testClosure02()  { $this->generic_test('Closure.02'); }
}
?>