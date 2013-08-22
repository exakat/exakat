<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Typehint extends Tokenizeur {
    /* 11 methods */

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
}
?>