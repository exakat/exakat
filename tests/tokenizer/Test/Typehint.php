<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Typehint extends Tokenizer {
    /* 18 methods */

    public function testTypehint01()  { $this->generic_test('Typehint.01'); }
    public function testTypehint02()  { $this->generic_test('Typehint.02'); }
    public function testTypehint03()  { $this->generic_test('Typehint.03'); }
    public function testTypehint04()  { $this->generic_test('Typehint.04'); }
    public function testTypehint05()  { $this->generic_test('Typehint.05'); }
    public function testTypehint06()  { $this->generic_test('Typehint.06'); }
    public function testTypehint07()  { $this->generic_test('Typehint.07'); }
    public function testTypehint08()  { $this->generic_test('Typehint.08'); }
    public function testTypehint09()  { $this->generic_test('Typehint.09'); }
    public function testTypehint10()  { $this->generic_test('Typehint.10'); }
    public function testTypehint11()  { $this->generic_test('Typehint.11'); }
    public function testTypehint12()  { $this->generic_test('Typehint.12'); }
    public function testTypehint13()  { $this->generic_test('Typehint.13'); }
    public function testTypehint14()  { $this->generic_test('Typehint.14'); }
    public function testTypehint15()  { $this->generic_test('Typehint.15'); }
    public function testTypehint16()  { $this->generic_test('Typehint.16'); }
    public function testTypehint17()  { $this->generic_test('Typehint.17'); }
    public function testTypehint18()  { $this->generic_test('Typehint.18'); }
}
?>