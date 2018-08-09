<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Goto extends Tokenizer {
    /* 11 methods */

    public function test_Goto01()  { $this->generic_test('_Goto.01'); }
    public function test_Goto02()  { $this->generic_test('_Goto.02'); }
    public function test_Goto03()  { $this->generic_test('_Goto.03'); }
    public function test_Goto04()  { $this->generic_test('_Goto.04'); }
    public function test_Goto05()  { $this->generic_test('_Goto.05'); }
    public function test_Goto06()  { $this->generic_test('_Goto.06'); }
    public function test_Goto07()  { $this->generic_test('_Goto.07'); }
    public function test_Goto08()  { $this->generic_test('_Goto.08'); }
    public function test_Goto09()  { $this->generic_test('_Goto.09'); }
    public function test_Goto10()  { $this->generic_test('_Goto.10'); }
    public function test_Goto11()  { $this->generic_test('_Goto.11'); }
}
?>