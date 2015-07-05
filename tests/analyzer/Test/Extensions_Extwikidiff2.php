<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extwikidiff2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extwikidiff201()  { $this->generic_test('Extensions_Extwikidiff2.01'); }
}
?>