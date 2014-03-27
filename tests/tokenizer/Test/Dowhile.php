<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Dowhile extends Tokenizer {
    /* 12 methods */

    public function testDowhile11()  { $this->generic_test('Dowhile.11'); }
    public function testDowhile12()  { $this->generic_test('Dowhile.12'); }
}
?>