<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class String extends Tokenizer {
    /* 25 methods */
    public function testString01()  { $this->generic_test('String.01'); }
    public function testString02()  { $this->generic_test('String.02'); }
    public function testString03()  { $this->generic_test('String.03'); }
    public function testString04()  { $this->generic_test('String.04'); }
    public function testString05()  { $this->generic_test('String.05'); }
    public function testString06()  { $this->generic_test('String.06'); }
    public function testString07()  { $this->generic_test('String.07'); }
    public function testString08()  { $this->generic_test('String.08'); }
    public function testString09()  { $this->generic_test('String.09'); }
    public function testString10()  { $this->generic_test('String.10'); }
    public function testString11()  { $this->generic_test('String.11'); }
    public function testString12()  { $this->generic_test('String.12'); }
    public function testString13()  { $this->generic_test('String.13'); }
    public function testString14()  { $this->generic_test('String.14'); }
    public function testString15()  { $this->generic_test('String.15'); }
    public function testString16()  { $this->generic_test('String.16'); }
    public function testString17()  { $this->generic_test('String.17'); }
    public function testString18()  { $this->generic_test('String.18'); }
    public function testString19()  { $this->generic_test('String.19'); }
    public function testString20()  { $this->generic_test('String.20'); }
    public function testString21()  { $this->generic_test('String.21'); }
    public function testString22()  { $this->generic_test('String.22'); }
    public function testString23()  { $this->generic_test('String.23'); }
    public function testString24()  { $this->generic_test('String.24'); }
    public function testString25()  { $this->generic_test('String.25'); }
}
?>