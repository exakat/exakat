<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LinuxOnlyFiles extends Analyzer {
    /* 1 methods */

    public function testPortability_LinuxOnlyFiles01()  { $this->generic_test('Portability/LinuxOnlyFiles.01'); }
}
?>