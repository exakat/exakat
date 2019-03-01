<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Define extends Tokenizer {
    /* 3 methods */

    public function test_Define01()  { $this->generic_test('_Define.01'); }
    public function test_Define02()  { $this->generic_test('_Define.02'); }
    public function test_Define03()  { $this->generic_test('_Define.03'); }
}
?>