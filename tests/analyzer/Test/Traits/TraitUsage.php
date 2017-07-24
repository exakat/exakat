<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Traits_TraitUsage extends Analyzer {
    /* 3 methods */

    public function testTraits_TraitUsage01()  { $this->generic_test('Traits_TraitUsage.01'); }
    public function testTraits_TraitUsage02()  { $this->generic_test('Traits/TraitUsage.02'); }
    public function testTraits_TraitUsage03()  { $this->generic_test('Traits/TraitUsage.03'); }
}
?>