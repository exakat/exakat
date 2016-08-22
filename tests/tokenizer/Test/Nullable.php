<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Nullable extends Tokenizer {
    /* 3 methods */

    public function testNullable01()  { $this->generic_test('Nullable.01'); }
    public function testNullable02()  { $this->generic_test('Nullable.02'); }
    public function testNullable03()  { $this->generic_test('Nullable.03'); }
}
?>