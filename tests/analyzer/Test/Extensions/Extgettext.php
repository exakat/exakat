<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extgettext extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extgettext01()  { $this->generic_test('Extensions_Extgettext.01'); }
    public function testExtensions_Extgettext02()  { $this->generic_test('Extensions/Extgettext.02'); }
}
?>