<?php

namespace Analyzer\Structures;

use Analyzer;

class FileUploadUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Variable")
             ->code('$_FILES');
        $this->prepareQuery();
        
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\is_uploaded_file', '\move_uploaded_file'));
        $this->prepareQuery();
    }
}

?>