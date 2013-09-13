<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Dowhile extends Tokenizer {
    /* 5 methods */

    public function testDowhile01()  { $this->generic_test('Dowhile.01'); }
    public function testDowhile02()  { $this->generic_test('Dowhile.02'); }
    public function testDowhile03()  { $this->generic_test('Dowhile.03'); }
    public function testDowhile04()  { $this->generic_test('Dowhile.04'); }
    public function testDowhile05()  { $this->generic_test('Dowhile.05'); }
}
?>