<?php

namespace Analyzer\Php;

use Analyzer;

class EllipsisUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Variable")
             ->is('variadic', 'true');
        $this->prepareQuery();
    }
}

?>
