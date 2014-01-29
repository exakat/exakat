<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extfileinfo extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'fileinfo.ini';
        
        parent::analyze();
    }
}

?>