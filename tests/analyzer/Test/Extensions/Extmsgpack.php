<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extmsgpack extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmsgpack01()  { $this->generic_test('Extensions/Extmsgpack.01'); }
}
?>