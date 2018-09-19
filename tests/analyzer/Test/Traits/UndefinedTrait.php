<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndefinedTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_UndefinedTrait01()  { $this->generic_test('Traits/UndefinedTrait.01'); }
    public function testTraits_UndefinedTrait02()  { $this->generic_test('Traits/UndefinedTrait.02'); }
}
?>