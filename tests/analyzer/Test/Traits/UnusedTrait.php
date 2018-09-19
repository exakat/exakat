<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Traits_UnusedTrait extends Analyzer {
    /* 8 methods */

    public function testTraits_UnusedTrait01()  { $this->generic_test('Traits/UnusedTrait.01'); }
    public function testTraits_UnusedTrait02()  { $this->generic_test('Traits/UnusedTrait.02'); }
    public function testTraits_UnusedTrait03()  { $this->generic_test('Traits/UnusedTrait.03'); }
    public function testTraits_UnusedTrait04()  { $this->generic_test('Traits/UnusedTrait.04'); }
    public function testTraits_UnusedTrait05()  { $this->generic_test('Traits/UnusedTrait.05'); }
    public function testTraits_UnusedTrait06()  { $this->generic_test('Traits/UnusedTrait.06'); }
    public function testTraits_UnusedTrait07()  { $this->generic_test('Traits/UnusedTrait.07'); }
    public function testTraits_UnusedTrait08()  { $this->generic_test('Traits/UnusedTrait.08'); }
}
?>