<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_SubstrFirst extends Analyzer {
    /* 3 methods */

    public function testPerformances_SubstrFirst01()  { $this->generic_test('Performances/SubstrFirst.01'); }
    public function testPerformances_SubstrFirst02()  { $this->generic_test('Performances/SubstrFirst.02'); }
    public function testPerformances_SubstrFirst03()  { $this->generic_test('Performances/SubstrFirst.03'); }
}
?>