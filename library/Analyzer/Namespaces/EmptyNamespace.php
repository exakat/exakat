<?php

namespace Analyzer\Namespaces;

use Analyzer;

class EmptyNamespace extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Namespace')
             ->raw('filter{it.out("NAMESPACE").has("code", "Global").any() == false}')
             ->outIs('BLOCK')
             ->atomIs('Void')
             ->raw('filter{it.out("ELEMENT").hasNot("atom", "Use").any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Namespace')
             ->raw('filter{it.out("NAMESPACE").has("code", "Global").any() == false}')
             ->outIs('BLOCK')
             ->atomIs('Sequence')
             ->raw('filter{it.out("ELEMENT").hasNot("atom", "Use").any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
