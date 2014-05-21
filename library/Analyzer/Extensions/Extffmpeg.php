<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extffmpeg extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ffmpeg.ini';
        
        parent::analyze();
    }
}

?>