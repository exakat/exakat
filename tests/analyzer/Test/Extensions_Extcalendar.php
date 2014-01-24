<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extcalendar extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extcalendar01()  { $this->generic_test('Extensions_Extcalendar.01'); }
}
?>