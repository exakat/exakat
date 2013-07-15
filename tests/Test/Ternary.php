<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ternary extends Tokenizeur {
    /* 13 methods */

    public function testTernary01()  { $this->generic_test('Ternary.01'); }
    public function testTernary02()  { $this->generic_test('Ternary.02'); }
    public function testTernary03()  { $this->generic_test('Ternary.03'); }
    public function testTernary04()  { $this->generic_test('Ternary.04'); }
    public function testTernary05()  { $this->generic_test('Ternary.05'); }
    public function testTernary06()  { $this->generic_test('Ternary.06'); }
    public function testTernary07()  { $this->generic_test('Ternary.07'); }
    public function testTernary08()  { $this->generic_test('Ternary.08'); }
    public function testTernary09()  { $this->generic_test('Ternary.09'); }
    public function testTernary10()  { $this->generic_test('Ternary.10'); }
    public function testTernary11()  { $this->generic_test('Ternary.11'); }
    public function testTernary12()  { $this->generic_test('Ternary.12'); }
    public function testTernary13()  { $this->generic_test('Ternary.13'); }
}
?>