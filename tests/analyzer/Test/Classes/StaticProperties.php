<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class StaticProperties extends Analyzer {
    /* 6 methods */

    public function testClasses_StaticProperties01()  { $this->generic_test('Classes_StaticProperties.01'); }
    public function testClasses_StaticProperties02()  { $this->generic_test('Classes_StaticProperties.02'); }
    public function testClasses_StaticProperties03()  { $this->generic_test('Classes_StaticProperties.03'); }
    public function testClasses_StaticProperties04()  { $this->generic_test('Classes_StaticProperties.04'); }
    public function testClasses_StaticProperties05()  { $this->generic_test('Classes/StaticProperties.05'); }
    public function testClasses_StaticProperties06()  { $this->generic_test('Classes/StaticProperties.06'); }
}
?>