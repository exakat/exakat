<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Methodcall extends Tokenizer {
    /* 20 methods */

    public function testMethodcall01()  { $this->generic_test('Methodcall.01'); }
    public function testMethodcall02()  { $this->generic_test('Methodcall.02'); }
    public function testMethodcall03()  { $this->generic_test('Methodcall.03'); }
    public function testMethodcall04()  { $this->generic_test('Methodcall.04'); }
    public function testMethodcall05()  { $this->generic_test('Methodcall.05'); }
    public function testMethodcall06()  { $this->generic_test('Methodcall.06'); }
    public function testMethodcall07()  { $this->generic_test('Methodcall.07'); }
    public function testMethodcall08()  { $this->generic_test('Methodcall.08'); }
    public function testMethodcall09()  { $this->generic_test('Methodcall.09'); }
    public function testMethodcall10()  { $this->generic_test('Methodcall.10'); }
    public function testMethodcall11()  { $this->generic_test('Methodcall.11'); }
    public function testMethodcall12()  { $this->generic_test('Methodcall.12'); }
    public function testMethodcall13()  { $this->generic_test('Methodcall.13'); }
    public function testMethodcall14()  { $this->generic_test('Methodcall.14'); }
    public function testMethodcall15()  { $this->generic_test('Methodcall.15'); }
    public function testMethodcall16()  { $this->generic_test('Methodcall.16'); }
    public function testMethodcall17()  { $this->generic_test('Methodcall.17'); }
    public function testMethodcall18()  { $this->generic_test('Methodcall.18'); }
    public function testMethodcall19()  { $this->generic_test('Methodcall.19'); }
    public function testMethodcall20()  { $this->generic_test('Methodcall.20'); }
}
?>