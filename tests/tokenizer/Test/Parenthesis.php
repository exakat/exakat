<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Parenthesis extends Tokenizer {
    /* 26 methods */
    public function testParenthesis01()  { $this->generic_test('Parenthesis.01'); }
    public function testParenthesis02()  { $this->generic_test('Parenthesis.02'); }
    public function testParenthesis03()  { $this->generic_test('Parenthesis.03'); }
    public function testParenthesis04()  { $this->generic_test('Parenthesis.04'); }
    public function testParenthesis05()  { $this->generic_test('Parenthesis.05'); }
    public function testParenthesis06()  { $this->generic_test('Parenthesis.06'); }
    public function testParenthesis07()  { $this->generic_test('Parenthesis.07'); }
    public function testParenthesis08()  { $this->generic_test('Parenthesis.08'); }
    public function testParenthesis09()  { $this->generic_test('Parenthesis.09'); }
    public function testParenthesis10()  { $this->generic_test('Parenthesis.10'); }
    public function testParenthesis11()  { $this->generic_test('Parenthesis.11'); }
    public function testParenthesis12()  { $this->generic_test('Parenthesis.12'); }
    public function testParenthesis13()  { $this->generic_test('Parenthesis.13'); }
    public function testParenthesis14()  { $this->generic_test('Parenthesis.14'); }
    public function testParenthesis15()  { $this->generic_test('Parenthesis.15'); }
    public function testParenthesis16()  { $this->generic_test('Parenthesis.16'); }
    public function testParenthesis17()  { $this->generic_test('Parenthesis.17'); }
    public function testParenthesis18()  { $this->generic_test('Parenthesis.18'); }
    public function testParenthesis19()  { $this->generic_test('Parenthesis.19'); }
    public function testParenthesis20()  { $this->generic_test('Parenthesis.20'); }
    public function testParenthesis21()  { $this->generic_test('Parenthesis.21'); }
    public function testParenthesis22()  { $this->generic_test('Parenthesis.22'); }
    public function testParenthesis23()  { $this->generic_test('Parenthesis.23'); }
    public function testParenthesis24()  { $this->generic_test('Parenthesis.24'); }
    public function testParenthesis25()  { $this->generic_test('Parenthesis.25'); }
    public function testParenthesis26()  { $this->generic_test('Parenthesis.26'); }
}
?>