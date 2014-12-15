<?php

namespace Analyzer\Php;

use Analyzer;

class EchoTagUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Phpcode")
             ->is('tag', "'<?='");
        $this->prepareQuery();
    }
}

?>
