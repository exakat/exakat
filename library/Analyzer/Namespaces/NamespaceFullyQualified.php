<?php

namespace Analyzer\Namespaces;

use Analyzer;

class NamespaceFullyQualified extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Nsname")
             ->outIs('SUBNAME')
             ->is('order', 0)
             ->tokenIs('T_NAMESPACE')
             ->back('first');
        $this->prepareQuery();
    }
}

?>