<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variadic extends Tokenizer {
    /* 1 methods */

    public function testVariadic01()  { $this->generic_test('Variadic.01'); }
}
?>