<?php

namespace Report\Content;

class ProcessedFileList extends \Report\Content {
    protected $array = array();

    public function collect() {
        $this->array = \Analyzer\Analyzer::$datastore->getCol('files', 'file');
        $this->array = array_map(function ($a) { return array($a); }, $this->array);

        return true;
    }
}

?>
