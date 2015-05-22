<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Var extends Tokenizer {
    /* 8 methods */

    public function test_Var01()  { $this->generic_test('_Var.01'); }
    public function test_Var02()  { $this->generic_test('_Var.02'); }
    public function test_Var03()  { $this->generic_test('_Var.03'); }
    public function test_Var04()  { $this->generic_test('_Var.04'); }
    public function test_Var05()  { $this->generic_test('_Var.05'); }
    public function test_Var06()  { $this->generic_test('_Var.06'); }
    public function test_Var07()  { $this->generic_test('_Var.07'); }
    public function test_Var08()  { $this->generic_test('_Var.08'); }
}
?>