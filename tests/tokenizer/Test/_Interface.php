<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Interface extends Tokenizer {
    /* 7 methods */

    public function test_Interface01()  { $this->generic_test('_Interface.01'); }
    public function test_Interface02()  { $this->generic_test('_Interface.02'); }
    public function test_Interface03()  { $this->generic_test('_Interface.03'); }
    public function test_Interface04()  { $this->generic_test('_Interface.04'); }
    public function test_Interface05()  { $this->generic_test('_Interface.05'); }
    public function test_Interface06()  { $this->generic_test('_Interface.06'); }
    public function test_Interface07()  { $this->generic_test('_Interface.07'); }
}
?>