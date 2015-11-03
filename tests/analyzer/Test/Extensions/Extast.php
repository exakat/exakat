<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extast extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extast01()  { $this->generic_test('Extensions_Extast.01'); }
}
?>