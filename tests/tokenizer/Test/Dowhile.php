<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Dowhile extends Tokenizer {
    /* 17 methods */

    public function testDowhile01()  { $this->generic_test('Dowhile.01'); }
    public function testDowhile02()  { $this->generic_test('Dowhile.02'); }
    public function testDowhile03()  { $this->generic_test('Dowhile.03'); }
    public function testDowhile04()  { $this->generic_test('Dowhile.04'); }
    public function testDowhile05()  { $this->generic_test('Dowhile.05'); }
    public function testDowhile06()  { $this->generic_test('Dowhile.06'); }
    public function testDowhile07()  { $this->generic_test('Dowhile.07'); }
    public function testDowhile08()  { $this->generic_test('Dowhile.08'); }
    public function testDowhile09()  { $this->generic_test('Dowhile.09'); }
    public function testDowhile10()  { $this->generic_test('Dowhile.10'); }
    public function testDowhile11()  { $this->generic_test('Dowhile.11'); }
    public function testDowhile12()  { $this->generic_test('Dowhile.12'); }
    public function testDowhile13()  { $this->generic_test('Dowhile.13'); }
    public function testDowhile14()  { $this->generic_test('Dowhile.14'); }
    public function testDowhile15()  { $this->generic_test('Dowhile.15'); }
    public function testDowhile16()  { $this->generic_test('Dowhile.16'); }
    public function testDowhile17()  { $this->generic_test('Dowhile.17'); }
}
?>