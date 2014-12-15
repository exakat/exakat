<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extexpect extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'expect.ini';
        
        parent::analyze();
    }
}

?>
