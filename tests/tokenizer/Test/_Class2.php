<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Class2 extends Tokenizer {
    /* 3 methods */

    public function test_Class201()  { $this->generic_test('_Class2.01'); }
    public function test_Class202()  { $this->generic_test('_Class2.02'); }
    public function test_Class203()  { $this->generic_test('_Class2.03'); }
}
?>