<?php

namespace Analyzer\Php;

use Analyzer;

class CaseForPSS extends Analyzer\Analyzer {
    protected $phpVersion = '5.5-';

    public function analyze() {
        $this->atomIs("Identifier")
             ->code(array('parent', 'self', 'static'), false)
             ->codeIsNot(array('parent', 'self', 'static'), true);

        parent::analyze();
    }
}

?>