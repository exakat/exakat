<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extctype extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extctype01()  { $this->generic_test('Extensions_Extctype.01'); }
}
?>