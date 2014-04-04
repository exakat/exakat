<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extxmlwriter extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extxmlwriter01()  { $this->generic_test('Extensions_Extxmlwriter.01'); }
    public function testExtensions_Extxmlwriter02()  { $this->generic_test('Extensions_Extxmlwriter.02'); }
}
?>