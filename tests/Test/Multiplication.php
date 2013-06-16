<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Multiplication extends Tokenizeur {
    /* 4 methods */
    public function testMultiplication01()  { $this->generic_test('Multiplication.01'); }
    public function testMultiplication02()  { $this->generic_test('Multiplication.02'); }
    public function testMultiplication03()  { $this->generic_test('Multiplication.03'); }
    public function testMultiplication04()  { $this->generic_test('Multiplication.04'); }
    public function testMultiplication05()  { $this->generic_test('Multiplication.05'); }
    public function testMultiplication06()  { $this->generic_test('Multiplication.06'); }
    public function testMultiplication07()  { $this->generic_test('Multiplication.07'); }
    public function testMultiplication08()  { $this->generic_test('Multiplication.08'); }
    public function testMultiplication09()  { $this->generic_test('Multiplication.09'); }
    public function testMultiplication10()  { $this->generic_test('Multiplication.10'); }
    public function testMultiplication11()  { $this->generic_test('Multiplication.11'); }
}
?>