<?php

namespace Analyzer\Type;

use Analyzer;

class HttpHeader extends Analyzer\Analyzer {
    public $HttpHeadersList = array(
'Accept',
'Accept-Charset',
'Accept-Encoding',
'Accept-Language',
'Accept-Ranges',
'Age',
'Allow',
'Authorization',
'Cache-Control',
'Connection',
'Content-Encoding',
'Content-Language',
'Content-Length',
'Content-Location',
'Content-MD5',
'Content-Range',
'Content-Type',
'Date',
'ETag',
'Expect',
'Expires',
'From',
'Host',
'If-Match',
'If-Modified-Since',
'If-None-Match',
'If-Range',
'If-Unmodified-Since',
'Last-Modified',
'Location',
'Max-Forwards',
'Pragma',
'Proxy-Authenticate',
'Proxy-Authorization',
'Range',
'Byte Ranges',
'Range Retrieval Requests',
'Referer',
'Retry-After',
'Server',
'TE',
'Trailer',
'Transfer-Encoding',
'Upgrade',
'User-Agent',
'Vary',
'Via',
'Warning',
'WWW-Authenticate    ',
    );

    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->regex('code', '[\'\"]('.join('|', $this->HttpHeadersList).'): .*[\'\"]');
        $this->prepareQuery();
    }
}

?>