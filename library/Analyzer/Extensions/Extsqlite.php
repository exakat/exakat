<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsqlite extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'sqlite.ini';
        
        parent::analyze();
    }
}

?>
