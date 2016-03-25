<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _As extends Tokenizer {
    /* 2 methods */

    public function test_As01()  { $this->generic_test('_As.01'); }
    public function test_As02()  { $this->generic_test('_As.02'); }
}
?>