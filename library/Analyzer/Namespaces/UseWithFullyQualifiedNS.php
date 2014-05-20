<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UseWithFullyQualifiedNS extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->atomIs('Nsname')
             ->is('absolutens', "'true'");
        $this->prepareQuery();

        $this->atomIs("Use")
             ->outIs('USE')
             ->outIs('ELEMENT')
             ->is('absolutens', "'true'");
    }
}

?>