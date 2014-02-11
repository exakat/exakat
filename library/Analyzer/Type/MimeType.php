<?php

namespace Analyzer\Type;

use Analyzer;

class MimeType extends Analyzer\Analyzer {
    public static $MimeTypes = array(
'application',
'audio',
'example',
'image',
'message',
'model',
'multipart',
'text',
'video',
    );

    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->regex('code', '[\'\"]('.join('|', Analyzer\Type\MimeType::$MimeTypes).')/[a-zA-Z0-9+\\\\-]+[\'\"]');
        $this->prepareQuery();
    }
}

?>