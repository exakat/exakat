<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Traits_UndefinedInsteadof extends Analyzer {
    /* 2 methods */

    public function testTraits_UndefinedInsteadof01()  { $this->generic_test('Traits/UndefinedInsteadof.01'); }
    public function testTraits_UndefinedInsteadof02()  { $this->generic_test('Traits/UndefinedInsteadof.02'); }
}
?>