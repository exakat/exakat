<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ConstantDefinition;

class Php72NewConstants extends ConstantDefinition {
    protected $phpVersion = '7.2-';

    public function analyze() {
        $this->constants = array('PHP_OS_FAMILY',
                                 'PHP_FLOAT_DIG',
                                 'PHP_FLOAT_EPSILON',
                                 'PHP_FLOAT_MAX',
                                 'PHP_FLOAT_MIN',
                                 'DATE_RFC7231',
                                 'PREG_UNMATCHED_AS_NULL',
                                 'SQLITE3_DETERMINISTIC',
                                 'CURLSSLOPT_NO_REVOKE',
                                 'CURLOPT_DEFAULT_PROTOCOL',
                                 'CURLOPT_STREAM_WEIGHT',
                                 'CURLMOPT_PUSHFUNCTION',
                                 'CURL_PUSH_OK',
                                 'CURL_PUSH_DENY',
                                 'CURL_HTTP_VERSION_2TLS',
                                 'CURLOPT_TFTP_NO_OPTIONS',
                                 'CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE',
                                 'CURLOPT_CONNECT_TO',
                                 'CURLOPT_TCP_FASTOPEN',
                                 'JSON_INVALID_UTF8_IGNORE',
                                 'JSON_INVALID_UTF8_SUBSTITUTE',
                                 'DNS_CAA',
                                 'LDAP_EXOP_START_TLS',
                                 'LDAP_EXOP_MODIFY_PASSWD',
                                 'LDAP_EXOP_REFRESH',
                                 'LDAP_EXOP_WHO_AM_I',
                                 'LDAP_EXOP_TURN',
                                 'PASSWORD_ARGON2I',
                                 'PASSWORD_ARGON2_DEFAULT_MEMORY_COST',
                                 'PASSWORD_ARGON2_DEFAULT_TIME_COST',
                                 'PASSWORD_ARGON2_DEFAULT_THREADS',
                                 'FILEINFO_EXTENSION',
                                 'IMG_EFFECT_MULTIPLY',
                                 'IMG_BMP',
        );
        parent::analyze();
    }
}

?>
