<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UselessAbstract extends Analyzer {
    /* 8 methods */

    public function testClasses_UselessAbstract01()  { $this->generic_test('Classes_UselessAbstract.01'); }
    public function testClasses_UselessAbstract02()  { $this->generic_test('Classes_UselessAbstract.02'); }
    public function testClasses_UselessAbstract03()  { $this->generic_test('Classes_UselessAbstract.03'); }
    public function testClasses_UselessAbstract04()  { $this->generic_test('Classes_UselessAbstract.04'); }
    public function testClasses_UselessAbstract05()  { $this->generic_test('Classes/UselessAbstract.05'); }
    public function testClasses_UselessAbstract06()  { $this->generic_test('Classes/UselessAbstract.06'); }
    public function testClasses_UselessAbstract07()  { $this->generic_test('Classes/UselessAbstract.07'); }
    public function testClasses_UselessAbstract08()  { $this->generic_test('Classes/UselessAbstract.08'); }
}
?>