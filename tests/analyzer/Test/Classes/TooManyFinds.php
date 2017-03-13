<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_TooManyFinds extends Analyzer {
    /* 4 methods */

    public function testClasses_TooManyFinds01()  { $this->generic_test('Classes/TooManyFinds.01'); }
    public function testClasses_TooManyFinds02()  { $this->generic_test('Classes/TooManyFinds.02'); }
    public function testClasses_TooManyFinds03()  { $this->generic_test('Classes/TooManyFinds.03'); }
    public function testClasses_TooManyFinds04()  { $this->generic_test('Classes/TooManyFinds.04'); }
}
?>