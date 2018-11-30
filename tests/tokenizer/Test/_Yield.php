<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Yield extends Tokenizer {
    /* 10 methods */

    public function test_Yield01()  { $this->generic_test('_Yield.01'); }
    public function test_Yield02()  { $this->generic_test('_Yield.02'); }
    public function test_Yield03()  { $this->generic_test('_Yield.03'); }
    public function test_Yield04()  { $this->generic_test('_Yield.04'); }
    public function test_Yield05()  { $this->generic_test('_Yield.05'); }
    public function test_Yield06()  { $this->generic_test('_Yield.06'); }
    public function test_Yield07()  { $this->generic_test('_Yield.07'); }
    public function test_Yield08()  { $this->generic_test('_Yield.08'); }
    public function test_Yield09()  { $this->generic_test('_Yield.09'); }
    public function test_Yield10()  { $this->generic_test('_Yield.10'); }
}
?>