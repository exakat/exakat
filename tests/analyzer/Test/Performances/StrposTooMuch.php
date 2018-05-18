<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_StrposTooMuch extends Analyzer {
    /* 1 methods */

    public function testPerformances_StrposTooMuch01()  { $this->generic_test('Performances/StrposTooMuch.01'); }
}
?>