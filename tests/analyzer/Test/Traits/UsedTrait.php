<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UsedTrait extends Analyzer {
    /* 6 methods */

    public function testTraits_UsedTrait01()  { $this->generic_test('Traits/UsedTrait.01'); }
    public function testTraits_UsedTrait02()  { $this->generic_test('Traits/UsedTrait.02'); }
    public function testTraits_UsedTrait03()  { $this->generic_test('Traits/UsedTrait.03'); }
    public function testTraits_UsedTrait04()  { $this->generic_test('Traits/UsedTrait.04'); }
    public function testTraits_UsedTrait05()  { $this->generic_test('Traits/UsedTrait.05'); }
    public function testTraits_UsedTrait06()  { $this->generic_test('Traits/UsedTrait.06'); }
}
?>