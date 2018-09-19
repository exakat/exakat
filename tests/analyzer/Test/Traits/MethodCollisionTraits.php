<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MethodCollisionTraits extends Analyzer {
    /* 5 methods */

    public function testTraits_MethodCollisionTraits01()  { $this->generic_test('Traits/MethodCollisionTraits.01'); }
    public function testTraits_MethodCollisionTraits02()  { $this->generic_test('Traits/MethodCollisionTraits.02'); }
    public function testTraits_MethodCollisionTraits03()  { $this->generic_test('Traits/MethodCollisionTraits.03'); }
    public function testTraits_MethodCollisionTraits04()  { $this->generic_test('Traits/MethodCollisionTraits.04'); }
    public function testTraits_MethodCollisionTraits05()  { $this->generic_test('Traits/MethodCollisionTraits.05'); }
}
?>