<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Interface extends Tokenizer {
    /* 16 methods */

    public function test_Interface01()  { $this->generic_test('_Interface.01'); }
    public function test_Interface02()  { $this->generic_test('_Interface.02'); }
    public function test_Interface03()  { $this->generic_test('_Interface.03'); }
    public function test_Interface04()  { $this->generic_test('_Interface.04'); }
    public function test_Interface05()  { $this->generic_test('_Interface.05'); }
    public function test_Interface06()  { $this->generic_test('_Interface.06'); }
    public function test_Interface07()  { $this->generic_test('_Interface.07'); }
    public function test_Interface08()  { $this->generic_test('_Interface.08'); }
    public function test_Interface09()  { $this->generic_test('_Interface.09'); }
    public function test_Interface10()  { $this->generic_test('_Interface.10'); }
    public function test_Interface11()  { $this->generic_test('_Interface.11'); }
    public function test_Interface12()  { $this->generic_test('_Interface.12'); }
    public function test_Interface13()  { $this->generic_test('_Interface.13'); }
    public function test_Interface14()  { $this->generic_test('_Interface.14'); }
    public function test_Interface15()  { $this->generic_test('_Interface.15'); }
    public function test_Interface16()  { $this->generic_test('_Interface.16'); }
}
?>