<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UnresolvedCatch extends Analyzer {
    /* 2 methods */

    public function testClasses_UnresolvedCatch01()  { $this->generic_test('Classes_UnresolvedCatch.01'); }
    public function testClasses_UnresolvedCatch02()  { $this->generic_test('Classes/UnresolvedCatch.02'); }
}
?>