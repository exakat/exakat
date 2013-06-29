<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Function extends Tokenizeur {
    /* 13 methods */

    public function test_Function01()  { $this->generic_test('_Function.01'); }
    public function test_Function02()  { $this->generic_test('_Function.02'); }
    public function test_Function03()  { $this->generic_test('_Function.03'); }
    public function test_Function04()  { $this->generic_test('_Function.04'); }
    public function test_Function05()  { $this->generic_test('_Function.05'); }
    public function test_Function06()  { $this->generic_test('_Function.06'); }
    public function test_Function07()  { $this->generic_test('_Function.07'); }
    public function test_Function08()  { $this->generic_test('_Function.08'); }
    public function test_Function09()  { $this->generic_test('_Function.09'); }
    public function test_Function10()  { $this->generic_test('_Function.10'); }
    public function test_Function11()  { $this->generic_test('_Function.11'); }
    public function test_Function12()  { $this->generic_test('_Function.12'); }
    public function test_Function13()  { $this->generic_test('_Function.13'); }
}
?>