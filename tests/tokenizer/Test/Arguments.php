<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Arguments extends Tokenizer {
    /* 27 methods */

    public function testArguments01()  { $this->generic_test('Arguments.01'); }
    public function testArguments02()  { $this->generic_test('Arguments.02'); }
    public function testArguments03()  { $this->generic_test('Arguments.03'); }
    public function testArguments04()  { $this->generic_test('Arguments.04'); }
    public function testArguments05()  { $this->generic_test('Arguments.05'); }
    public function testArguments06()  { $this->generic_test('Arguments.06'); }
    public function testArguments07()  { $this->generic_test('Arguments.07'); }
    public function testArguments08()  { $this->generic_test('Arguments.08'); }
    public function testArguments09()  { $this->generic_test('Arguments.09'); }
    public function testArguments10()  { $this->generic_test('Arguments.10'); }
    public function testArguments11()  { $this->generic_test('Arguments.11'); }
    public function testArguments12()  { $this->generic_test('Arguments.12'); }
    public function testArguments13()  { $this->generic_test('Arguments.13'); }
    public function testArguments14()  { $this->generic_test('Arguments.14'); }
    public function testArguments15()  { $this->generic_test('Arguments.15'); }
    public function testArguments16()  { $this->generic_test('Arguments.16'); }
    public function testArguments17()  { $this->generic_test('Arguments.17'); }
    public function testArguments18()  { $this->generic_test('Arguments.18'); }
    public function testArguments19()  { $this->generic_test('Arguments.19'); }
    public function testArguments20()  { $this->generic_test('Arguments.20'); }
    public function testArguments21()  { $this->generic_test('Arguments.21'); }
    public function testArguments22()  { $this->generic_test('Arguments.22'); }
    public function testArguments23()  { $this->generic_test('Arguments.23'); }
    public function testArguments24()  { $this->generic_test('Arguments.24'); }
    public function testArguments25()  { $this->generic_test('Arguments.25'); }
    public function testArguments26()  { $this->generic_test('Arguments.26'); }
    public function testArguments27()  { $this->generic_test('Arguments.27'); }
}
?>