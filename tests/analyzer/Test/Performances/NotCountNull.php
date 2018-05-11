<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_NotCountNull extends Analyzer {
    /* 2 methods */

    public function testPerformances_NotCountNull01()  { $this->generic_test('Performances/NotCountNull.01'); }
    public function testPerformances_NotCountNull02()  { $this->generic_test('Performances/NotCountNull.02'); }
}
?>