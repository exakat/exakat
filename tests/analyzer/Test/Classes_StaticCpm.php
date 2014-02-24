<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_StaticCpm extends Analyzer {
    /* 1 methods */

    public function testClasses_StaticCpm01()  { $this->generic_test('Classes_StaticCpm.01'); }
}
?>