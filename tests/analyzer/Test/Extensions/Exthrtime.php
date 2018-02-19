<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Exthrtime extends Analyzer {
    /* 2 methods */

    public function testExtensions_Exthrtime01()  { $this->generic_test('Extensions/Exthrtime.01'); }
    public function testExtensions_Exthrtime02()  { $this->generic_test('Extensions/Exthrtime.02'); }
}
?>