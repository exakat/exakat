<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Insteadof extends Tokenizer {
    /* 4 methods */

    public function test_Insteadof01()  { $this->generic_test('_Insteadof.01'); }
    public function test_Insteadof02()  { $this->generic_test('_Insteadof.02'); }
    public function test_Insteadof03()  { $this->generic_test('_Insteadof.03'); }
    public function test_Insteadof04()  { $this->generic_test('_Insteadof.04'); }
}
?>