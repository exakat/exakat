<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_CouldUseShortAssignation extends Analyzer {
    /* 6 methods */

    public function testStructures_CouldUseShortAssignation01()  { $this->generic_test('Structures_CouldUseShortAssignation.01'); }
    public function testStructures_CouldUseShortAssignation02()  { $this->generic_test('Structures_CouldUseShortAssignation.02'); }
    public function testStructures_CouldUseShortAssignation03()  { $this->generic_test('Structures/CouldUseShortAssignation.03'); }
    public function testStructures_CouldUseShortAssignation04()  { $this->generic_test('Structures/CouldUseShortAssignation.04'); }
    public function testStructures_CouldUseShortAssignation05()  { $this->generic_test('Structures/CouldUseShortAssignation.05'); }
    public function testStructures_CouldUseShortAssignation06()  { $this->generic_test('Structures/CouldUseShortAssignation.06'); }
}
?>