<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnresolvedCatch extends Analyzer {
    /* 2 methods */

    public function testClasses_UnresolvedCatch01()  { $this->generic_test('Classes_UnresolvedCatch.01'); }
    public function testClasses_UnresolvedCatch02()  { $this->generic_test('Classes/UnresolvedCatch.02'); }
}
?>