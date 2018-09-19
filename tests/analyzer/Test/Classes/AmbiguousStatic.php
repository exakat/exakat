<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class AmbiguousStatic extends Analyzer {
    /* 5 methods */

    public function testClasses_AmbiguousStatic01()  { $this->generic_test('Classes/AmbiguousStatic.01'); }
    public function testClasses_AmbiguousStatic02()  { $this->generic_test('Classes/AmbiguousStatic.02'); }
    public function testClasses_AmbiguousStatic03()  { $this->generic_test('Classes/AmbiguousStatic.03'); }
    public function testClasses_AmbiguousStatic04()  { $this->generic_test('Classes/AmbiguousStatic.04'); }
    public function testClasses_AmbiguousStatic05()  { $this->generic_test('Classes/AmbiguousStatic.05'); }
}
?>