<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_UnusedConstants extends Analyzer {
    /* 7 methods */

    public function testConstants_UnusedConstants01()  { $this->generic_test('Constants_UnusedConstants.01'); }
    public function testConstants_UnusedConstants02()  { $this->generic_test('Constants_UnusedConstants.02'); }
    public function testConstants_UnusedConstants03()  { $this->generic_test('Constants/UnusedConstants.03'); }
    public function testConstants_UnusedConstants04()  { $this->generic_test('Constants/UnusedConstants.04'); }
    public function testConstants_UnusedConstants05()  { $this->generic_test('Constants/UnusedConstants.05'); }
    public function testConstants_UnusedConstants06()  { $this->generic_test('Constants/UnusedConstants.06'); }
    public function testConstants_UnusedConstants07()  { $this->generic_test('Constants/UnusedConstants.07'); }
}
?>