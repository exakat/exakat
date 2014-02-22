<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_NonPPP extends Analyzer {
    /* 2 methods */

    public function testClasses_NonPPP01()  { $this->generic_test('Classes_NonPPP.01'); }
    public function testClasses_NonPPP02()  { $this->generic_test('Classes_NonPPP.02'); }
}
?>