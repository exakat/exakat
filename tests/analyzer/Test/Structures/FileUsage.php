<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FileUsage extends Analyzer {
    /* 3 methods */

    public function testStructures_FileUsage01()  { $this->generic_test('Structures_FileUsage.01'); }
    public function testStructures_FileUsage02()  { $this->generic_test('Structures/FileUsage.02'); }
    public function testStructures_FileUsage03()  { $this->generic_test('Structures/FileUsage.03'); }
}
?>