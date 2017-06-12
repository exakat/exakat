<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extgender extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgender01()  { $this->generic_test('Extensions/Extgender.01'); }
}
?>