<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_MakeOneCall extends Analyzer {
    /* 6 methods */

    public function testPerformances_MakeOneCall01()  { $this->generic_test('Performances/MakeOneCall.01'); }
    public function testPerformances_MakeOneCall02()  { $this->generic_test('Performances/MakeOneCall.02'); }
    public function testPerformances_MakeOneCall03()  { $this->generic_test('Performances/MakeOneCall.03'); }
    public function testPerformances_MakeOneCall04()  { $this->generic_test('Performances/MakeOneCall.04'); }
    public function testPerformances_MakeOneCall05()  { $this->generic_test('Performances/MakeOneCall.05'); }
    public function testPerformances_MakeOneCall06()  { $this->generic_test('Performances/MakeOneCall.06'); }
}
?>