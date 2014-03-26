<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Final extends Tokenizer {
    /* 3 methods */

    public function test_Final01()  { $this->generic_test('_Final.01'); }
    public function test_Final02()  { $this->generic_test('_Final.02'); }
    public function test_Final03()  { $this->generic_test('_Final.03'); }
}
?>