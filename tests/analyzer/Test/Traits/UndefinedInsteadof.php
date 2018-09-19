<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndefinedInsteadof extends Analyzer {
    /* 2 methods */

    public function testTraits_UndefinedInsteadof01()  { $this->generic_test('Traits/UndefinedInsteadof.01'); }
    public function testTraits_UndefinedInsteadof02()  { $this->generic_test('Traits/UndefinedInsteadof.02'); }
}
?>