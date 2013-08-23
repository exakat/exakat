<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Instanceof extends Tokenizeur {
    /* 4 methods */

    public function test_Instanceof01()  { $this->generic_test('_Instanceof.01'); }
    public function test_Instanceof02()  { $this->generic_test('_Instanceof.02'); }
    public function test_Instanceof03()  { $this->generic_test('_Instanceof.03'); }

    public function test_Instanceof04()  { $this->generic_test('_Instanceof.04'); }
}
?>