<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Melis_MakeTypeAString extends Analyzer {
    /* 2 methods */

    public function testMelis_MakeTypeAString01()  { $this->generic_test('Melis/MakeTypeAString.01'); }
    public function testMelis_MakeTypeAString02()  { $this->generic_test('Melis/MakeTypeAString.02'); }
}
?>