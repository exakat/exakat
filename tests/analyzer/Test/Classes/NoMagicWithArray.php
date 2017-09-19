<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_NoMagicWithArray extends Analyzer {
    /* 4 methods */

    public function testClasses_NoMagicWithArray01()  { $this->generic_test('Classes/NoMagicWithArray.01'); }
    public function testClasses_NoMagicWithArray02()  { $this->generic_test('Classes/NoMagicWithArray.02'); }
    public function testClasses_NoMagicWithArray03()  { $this->generic_test('Classes/NoMagicWithArray.03'); }
    public function testClasses_NoMagicWithArray04()  { $this->generic_test('Classes/NoMagicWithArray.04'); }
}
?>