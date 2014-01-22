<?php

namespace Analyzer\Type;

use Analyzer;

class HttpHeader extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->regex('code', '[\'\"](Content-Type|WWW-Authenticate|Cache-Control|Robots-Tag|Content-Transfer-Encoding|Access-Control-Allow-Origin|Location|Retry-After|Content-Range|Content-Encoding|Frame-Options|Content-Disposition|Vary|X-Frame-Options|X-XSS-Protection|Strict-Transport-Security): .*[\'\"]');
        $this->prepareQuery();
    }
}

?>