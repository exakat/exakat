<?php

namespace Analyzer\Namespaces;

use Analyzer;

class Namespacesnames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Namespace")
             ->out('NAMESPACE');
//        $this->prepareQuery();
    }
}

?>