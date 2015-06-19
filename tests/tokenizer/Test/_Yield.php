<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Yield extends Tokenizer {
    /* 4 methods */

    public function test_Yield01()  { $this->generic_test('_Yield.01'); }
    public function test_Yield02()  { $this->generic_test('_Yield.02'); }
    public function test_Yield03()  { $this->generic_test('_Yield.03'); }
    public function test_Yield04()  { $this->generic_test('_Yield.04'); }
}
?>