<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Declare extends Tokenizer {
    /* 16 methods */

    public function test_Declare01()  { $this->generic_test('_Declare.01'); }
    public function test_Declare02()  { $this->generic_test('_Declare.02'); }
    public function test_Declare03()  { $this->generic_test('_Declare.03'); }
    public function test_Declare04()  { $this->generic_test('_Declare.04'); }
    public function test_Declare05()  { $this->generic_test('_Declare.05'); }
    public function test_Declare06()  { $this->generic_test('_Declare.06'); }
    public function test_Declare07()  { $this->generic_test('_Declare.07'); }
    public function test_Declare08()  { $this->generic_test('_Declare.08'); }
    public function test_Declare09()  { $this->generic_test('_Declare.09'); }
    public function test_Declare10()  { $this->generic_test('_Declare.10'); }
    public function test_Declare11()  { $this->generic_test('_Declare.11'); }
    public function test_Declare12()  { $this->generic_test('_Declare.12'); }
    public function test_Declare13()  { $this->generic_test('_Declare.13'); }
    public function test_Declare14()  { $this->generic_test('_Declare.14'); }
    public function test_Declare15()  { $this->generic_test('_Declare.15'); }
    public function test_Declare16()  { $this->generic_test('_Declare.16'); }
}
?>