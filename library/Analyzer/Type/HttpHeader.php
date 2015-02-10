<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


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
'WWW-Authenticate',
    );

    public function dependsOn() {
        return array('Analyzer\\Type\\String');
    }
    
    public function analyze() {
        $this->atomIs('String')
             ->regex('code', '[\'\"]('.implode('|', $this->HttpHeadersList).'): .*[\'\"]');
        $this->prepareQuery();
    }
}

?>
