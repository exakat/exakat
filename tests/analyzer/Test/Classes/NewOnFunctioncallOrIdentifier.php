<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NewOnFunctioncallOrIdentifier extends Analyzer {
    /* 3 methods */

    public function testClasses_NewOnFunctioncallOrIdentifier01()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.01'); }
    public function testClasses_NewOnFunctioncallOrIdentifier02()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.02'); }
    public function testClasses_NewOnFunctioncallOrIdentifier03()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.03'); }
}
?>