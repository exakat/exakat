<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Not extends Tokenizeur {
    /* 15 methods */
    public function testNot01()  { $this->generic_test('Not.01'); }
    public function testNot02()  { $this->generic_test('Not.02'); }
    public function testNot03()  { $this->generic_test('Not.03'); }
    public function testNot04()  { $this->generic_test('Not.04'); }
    public function testNot05()  { $this->generic_test('Not.05'); }
    public function testNot06()  { $this->generic_test('Not.06'); }
    public function testNot07()  { $this->generic_test('Not.07'); }
    public function testNot08()  { $this->generic_test('Not.08'); }
    public function testNot09()  { $this->generic_test('Not.09'); }
    public function testNot10()  { $this->generic_test('Not.10'); }
    public function testNot11()  { $this->generic_test('Not.11'); }
    public function testNot12()  { $this->generic_test('Not.12'); }
    public function testNot13()  { $this->generic_test('Not.13'); }
    public function testNot14()  { $this->generic_test('Not.14'); }
    public function testNot15()  { $this->generic_test('Not.15'); }
}
?>