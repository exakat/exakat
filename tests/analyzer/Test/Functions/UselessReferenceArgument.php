<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UselessReferenceArgument extends Analyzer {
    /* 1 methods */

    public function testFunctions_UselessReferenceArgument01()  { $this->generic_test('Functions/UselessReferenceArgument.01'); }
}
?>