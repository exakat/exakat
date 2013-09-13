<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Methodcall extends Tokenizer {
    /* 8 methods */

    public function testMethodcall01()  { $this->generic_test('Methodcall.01'); }
    public function testMethodcall02()  { $this->generic_test('Methodcall.02'); }
    public function testMethodcall03()  { $this->generic_test('Methodcall.03'); }
    public function testMethodcall04()  { $this->generic_test('Methodcall.04'); }
    public function testMethodcall05()  { $this->generic_test('Methodcall.05'); }
    public function testMethodcall06()  { $this->generic_test('Methodcall.06'); }
    public function testMethodcall07()  { $this->generic_test('Methodcall.07'); }
    public function testMethodcall08()  { $this->generic_test('Methodcall.08'); }
}
?>