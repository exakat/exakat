<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Yieldfrom extends Tokenizer {
    /* 4 methods */

    public function test_Yieldfrom01()  { $this->generic_test('_Yieldfrom.01'); }
    public function test_Yieldfrom02()  { $this->generic_test('_Yieldfrom.02'); }
    public function test_Yieldfrom03()  { $this->generic_test('_Yieldfrom.03'); }
    public function test_Yieldfrom04()  { $this->generic_test('_Yieldfrom.04'); }
}
?>