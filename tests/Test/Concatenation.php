<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Concatenation extends Tokenizeur {
    /* 19 methods */
    public function testConcatenation01()  { $this->generic_test('Concatenation.01'); }
    public function testConcatenation02()  { $this->generic_test('Concatenation.02'); }
    public function testConcatenation03()  { $this->generic_test('Concatenation.03'); }
    public function testConcatenation04()  { $this->generic_test('Concatenation.04'); }
    public function testConcatenation05()  { $this->generic_test('Concatenation.05'); }
    public function testConcatenation06()  { $this->generic_test('Concatenation.06'); }
    public function testConcatenation07()  { $this->generic_test('Concatenation.07'); }
    public function testConcatenation08()  { $this->generic_test('Concatenation.08'); }
    public function testConcatenation09()  { $this->generic_test('Concatenation.09'); }
    public function testConcatenation10()  { $this->generic_test('Concatenation.10'); }
    public function testConcatenation11()  { $this->generic_test('Concatenation.11'); }
    public function testConcatenation12()  { $this->generic_test('Concatenation.12'); }
    public function testConcatenation13()  { $this->generic_test('Concatenation.13'); }
    public function testConcatenation14()  { $this->generic_test('Concatenation.14'); }
    public function testConcatenation15()  { $this->generic_test('Concatenation.15'); }
    public function testConcatenation16()  { $this->generic_test('Concatenation.16'); }
    public function testConcatenation18()  { $this->generic_test('Concatenation.18'); }
    public function testConcatenation17()  { $this->generic_test('Concatenation.17'); }
    public function testConcatenation19()  { $this->generic_test('Concatenation.19'); }
}
?>