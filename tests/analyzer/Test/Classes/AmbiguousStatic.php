<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_AmbiguousStatic extends Analyzer {
    /* 5 methods */

    public function testClasses_AmbiguousStatic01()  { $this->generic_test('Classes/AmbiguousStatic.01'); }
    public function testClasses_AmbiguousStatic02()  { $this->generic_test('Classes/AmbiguousStatic.02'); }
    public function testClasses_AmbiguousStatic03()  { $this->generic_test('Classes/AmbiguousStatic.03'); }
    public function testClasses_AmbiguousStatic04()  { $this->generic_test('Classes/AmbiguousStatic.04'); }
    public function testClasses_AmbiguousStatic05()  { $this->generic_test('Classes/AmbiguousStatic.05'); }
}
?>