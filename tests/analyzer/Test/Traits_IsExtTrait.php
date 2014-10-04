<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Traits_IsExtTrait extends Analyzer {
    /* 1 methods */

    public function testTraits_IsExtTrait01()  { $this->generic_test('Traits_IsExtTrait.01'); }
}
?>