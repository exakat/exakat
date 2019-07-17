<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Exit extends Tokenizer {
    /* 2 methods */

    public function test_Exit01()  { $this->generic_test('_Exit.01'); }
    public function test_Exit02()  { $this->generic_test('_Exit.02'); }
}
?>