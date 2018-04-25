<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Files_InclusionWrongCase extends Analyzer {
    /* 14 methods */

    public function testFiles_InclusionWrongCase01()  { $this->generic_test('Files/InclusionWrongCase.01'); }
    public function testFiles_InclusionWrongCase02()  { $this->generic_test('Files/InclusionWrongCase.02'); }
    public function testFiles_InclusionWrongCase03()  { $this->generic_test('Files/InclusionWrongCase.03'); }
    public function testFiles_InclusionWrongCase04()  { $this->generic_test('Files/InclusionWrongCase.04'); }
    public function testFiles_InclusionWrongCase05()  { $this->generic_test('Files/InclusionWrongCase.05'); }
    public function testFiles_InclusionWrongCase06()  { $this->generic_test('Files/InclusionWrongCase.06'); }
    public function testFiles_InclusionWrongCase07()  { $this->generic_test('Files/InclusionWrongCase.07'); }
    public function testFiles_InclusionWrongCase08()  { $this->generic_test('Files/InclusionWrongCase.08'); }
    public function testFiles_InclusionWrongCase09()  { $this->generic_test('Files/InclusionWrongCase.09'); }
    public function testFiles_InclusionWrongCase10()  { $this->generic_test('Files/InclusionWrongCase.10'); }
    public function testFiles_InclusionWrongCase11()  { $this->generic_test('Files/InclusionWrongCase.11'); }
    public function testFiles_InclusionWrongCase12()  { $this->generic_test('Files/InclusionWrongCase.12'); }
    public function testFiles_InclusionWrongCase13()  { $this->generic_test('Files/InclusionWrongCase.13'); }
    public function testFiles_InclusionWrongCase14()  { $this->generic_test('Files/InclusionWrongCase.14'); }
}
?>