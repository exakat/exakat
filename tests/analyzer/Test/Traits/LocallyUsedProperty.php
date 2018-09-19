<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LocallyUsedProperty extends Analyzer {
    /* 3 methods */

    public function testTraits_LocallyUsedProperty01()  { $this->generic_test('Traits/LocallyUsedProperty.01'); }
    public function testTraits_LocallyUsedProperty02()  { $this->generic_test('Traits/LocallyUsedProperty.02'); }
    public function testTraits_LocallyUsedProperty03()  { $this->generic_test('Traits/LocallyUsedProperty.03'); }
}
?>