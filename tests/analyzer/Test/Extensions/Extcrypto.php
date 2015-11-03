<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extcrypto extends Analyzer {
    /* 3 methods */

    public function testExtensions_Extcrypto01()  { $this->generic_test('Extensions_Extcrypto.01'); }
    public function testExtensions_Extcrypto02()  { $this->generic_test('Extensions_Extcrypto.02'); }
    public function testExtensions_Extcrypto03()  { $this->generic_test('Extensions_Extcrypto.03'); }
}
?>