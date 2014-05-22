<?php

namespace Analyzer\Namespaces;

use Analyzer;

class NamespaceFullyQualified extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Nsname")
             ->is('order', '0')
             ->inIs('ELEMENT')
             ->inIs('CODE')
             ->is('root', "'true'")
             ->back('first');
        $this->prepareQuery();
    }
}

?>