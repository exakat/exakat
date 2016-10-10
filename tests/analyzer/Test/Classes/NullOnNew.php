<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_NullOnNew extends Analyzer {
    /* 2 methods */

    public function testClasses_NullOnNew01()  { $this->generic_test('Classes_NullOnNew.01'); }
    public function testClasses_NullOnNew02()  { $this->generic_test('Classes/NullOnNew.02'); }
}
?>