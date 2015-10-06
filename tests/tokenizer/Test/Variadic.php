<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variadic extends Tokenizer {
    /* 3 methods */

    public function testVariadic01()  { $this->generic_test('Variadic.01'); }
    public function testVariadic02()  { $this->generic_test('Variadic.02'); }
    public function testVariadic03()  { $this->generic_test('Variadic.03'); }
}
?>