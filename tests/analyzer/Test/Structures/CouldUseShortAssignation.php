<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldUseShortAssignation extends Analyzer {
    /* 9 methods */

    public function testStructures_CouldUseShortAssignation01()  { $this->generic_test('Structures_CouldUseShortAssignation.01'); }
    public function testStructures_CouldUseShortAssignation02()  { $this->generic_test('Structures_CouldUseShortAssignation.02'); }
    public function testStructures_CouldUseShortAssignation03()  { $this->generic_test('Structures/CouldUseShortAssignation.03'); }
    public function testStructures_CouldUseShortAssignation04()  { $this->generic_test('Structures/CouldUseShortAssignation.04'); }
    public function testStructures_CouldUseShortAssignation05()  { $this->generic_test('Structures/CouldUseShortAssignation.05'); }
    public function testStructures_CouldUseShortAssignation06()  { $this->generic_test('Structures/CouldUseShortAssignation.06'); }
    public function testStructures_CouldUseShortAssignation07()  { $this->generic_test('Structures/CouldUseShortAssignation.07'); }
    public function testStructures_CouldUseShortAssignation08()  { $this->generic_test('Structures/CouldUseShortAssignation.08'); }
    public function testStructures_CouldUseShortAssignation09()  { $this->generic_test('Structures/CouldUseShortAssignation.09'); }
}
?>