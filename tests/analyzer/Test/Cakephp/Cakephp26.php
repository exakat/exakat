<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cakephp26 extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cakephp2601()  { $this->generic_test('Cakephp/Cakephp26.01'); }
}
?>