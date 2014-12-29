<?php

namespace Analyzer\Namespaces;

use Analyzer;

class NamespaceUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Namespace')
             ->outIs('NAMESPACE');
        $this->prepareQuery();
    }
}

?>
