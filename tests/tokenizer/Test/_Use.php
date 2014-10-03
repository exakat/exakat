<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Use extends Tokenizer {
    /* 20 methods */

    public function test_Use01()  { $this->generic_test('_Use.01'); }
    public function test_Use02()  { $this->generic_test('_Use.02'); }
    public function test_Use03()  { $this->generic_test('_Use.03'); }
    public function test_Use04()  { $this->generic_test('_Use.04'); }
    public function test_Use05()  { $this->generic_test('_Use.05'); }
    public function test_Use06()  { $this->generic_test('_Use.06'); }
    public function test_Use07()  { $this->generic_test('_Use.07'); }
    public function test_Use08()  { $this->generic_test('_Use.08'); }
    public function test_Use09()  { $this->generic_test('_Use.09'); }
    public function test_Use10()  { $this->generic_test('_Use.10'); }
    public function test_Use11()  { $this->generic_test('_Use.11'); }
    public function test_Use12()  { $this->generic_test('_Use.12'); }
    public function test_Use13()  { $this->generic_test('_Use.13'); }
    public function test_Use14()  { $this->generic_test('_Use.14'); }
    public function test_Use15()  { $this->generic_test('_Use.15'); }
    public function test_Use16()  { $this->generic_test('_Use.16'); }
    public function test_Use17()  { $this->generic_test('_Use.17'); }
    public function test_Use18()  { $this->generic_test('_Use.18'); }
    public function test_Use19()  { $this->generic_test('_Use.19'); }
    public function test_Use20()  { $this->generic_test('_Use.20'); }
}
?>