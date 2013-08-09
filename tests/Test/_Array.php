<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Array extends Tokenizeur {
    /* 9 methods */
    public function test_Array01()  { $this->generic_test('_Array.01'); }
    public function test_Array02()  { $this->generic_test('_Array.02'); }
    public function test_Array03()  { $this->generic_test('_Array.03'); }
    public function test_Array04()  { $this->generic_test('_Array.04'); }
    public function test_Array05()  { $this->generic_test('_Array.05'); }
    public function test_Array06()  { $this->generic_test('_Array.06'); }
    public function test_Array07()  { $this->generic_test('_Array.07'); }
    public function test_Array08()  { $this->generic_test('_Array.08'); }
    public function test_Array09()  { $this->generic_test('_Array.09'); }
}
?>