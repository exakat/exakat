<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extreflection extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extreflection01()  { $this->generic_test('Extensions_Extreflection.01'); }
    public function testExtensions_Extreflection02()  { $this->generic_test('Extensions/Extreflection.02'); }
}
?>