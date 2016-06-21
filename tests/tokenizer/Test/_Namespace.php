<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Namespace extends Tokenizer {
    /* 15 methods */

    public function test_Namespace01()  { $this->generic_test('_Namespace.01'); }
    public function test_Namespace02()  { $this->generic_test('_Namespace.02'); }
    public function test_Namespace03()  { $this->generic_test('_Namespace.03'); }
    public function test_Namespace04()  { $this->generic_test('_Namespace.04'); }
    public function test_Namespace05()  { $this->generic_test('_Namespace.05'); }
    public function test_Namespace06()  { $this->generic_test('_Namespace.06'); }
    public function test_Namespace07()  { $this->generic_test('_Namespace.07'); }
    public function test_Namespace08()  { $this->generic_test('_Namespace.08'); }
    public function test_Namespace09()  { $this->generic_test('_Namespace.09'); }
    public function test_Namespace10()  { $this->generic_test('_Namespace.10'); }
    public function test_Namespace11()  { $this->generic_test('_Namespace.11'); }
    public function test_Namespace12()  { $this->generic_test('_Namespace.12'); }
    public function test_Namespace13()  { $this->generic_test('_Namespace.13'); }
    public function test_Namespace14()  { $this->generic_test('_Namespace.14'); }
    public function test_Namespace15()  { $this->generic_test('_Namespace.15'); }
}
?>