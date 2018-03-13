<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_PropertyCouldBeLocal extends Analyzer {
    /* 2 methods */

    public function testClasses_PropertyCouldBeLocal01()  { $this->generic_test('Classes/PropertyCouldBeLocal.01'); }
    public function testClasses_PropertyCouldBeLocal02()  { $this->generic_test('Classes/PropertyCouldBeLocal.02'); }
}
?>