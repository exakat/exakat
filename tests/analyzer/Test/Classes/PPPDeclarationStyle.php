<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PPPDeclarationStyle extends Analyzer {
    /* 1 methods */

    public function testClasses_PPPDeclarationStyle01()  { $this->generic_test('Classes/PPPDeclarationStyle.01'); }
}
?>