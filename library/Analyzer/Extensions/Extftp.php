<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extftp extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ftp.ini';
        
        parent::analyze();
    }
}

?>
