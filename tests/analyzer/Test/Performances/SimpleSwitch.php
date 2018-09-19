<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class SimpleSwitch extends Analyzer {
    /* 4 methods */

    public function testPerformances_SimpleSwitch01()  { $this->generic_test('Performances/SimpleSwitch.01'); }
    public function testPerformances_SimpleSwitch02()  { $this->generic_test('Performances/SimpleSwitch.02'); }
    public function testPerformances_SimpleSwitch03()  { $this->generic_test('Performances/SimpleSwitch.03'); }
    public function testPerformances_SimpleSwitch04()  { $this->generic_test('Performances/SimpleSwitch.04'); }
}
?>