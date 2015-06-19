<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Continue extends Tokenizer {
    /* 5 methods */

    public function test_Continue01()  { $this->generic_test('_Continue.01'); }
    public function test_Continue02()  { $this->generic_test('_Continue.02'); }
    public function test_Continue03()  { $this->generic_test('_Continue.03'); }
    public function test_Continue04()  { $this->generic_test('_Continue.04'); }
    public function test_Continue05()  { $this->generic_test('_Continue.05'); }
}
?>