<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Print extends Tokenizer {
    /* 2 methods */

    public function test_Print01()  { $this->generic_test('_Print.01'); }
    public function test_Print01()  { $this->generic_test('_Print.01'); }
    public function test_Print02()  { $this->generic_test('_Print.02'); }
}
?>