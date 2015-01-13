<?php

namespace Analyzer\Type;

use Analyzer;

class MimeType extends Analyzer\Analyzer {
    public static $mimeTypes = array(
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

    public function dependsOn() {
        return array('Analyzer\\Type\\String');
    }
    
    public function analyze() {
        $this->atomIs('String')
             ->regex('code', '[\'\"]('.implode('|', self::$mimeTypes).')/[a-zA-Z0-9+\\\\-]+[\'\"]');
        $this->prepareQuery();
    }
}

?>
