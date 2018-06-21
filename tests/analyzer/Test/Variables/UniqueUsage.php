<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_UniqueUsage extends Analyzer {
    /* 2 methods */

    public function testVariables_UniqueUsage01()  { $this->generic_test('Variables/UniqueUsage.01'); }
    public function testVariables_UniqueUsage02()  { $this->generic_test('Variables/UniqueUsage.02'); }
}
?>