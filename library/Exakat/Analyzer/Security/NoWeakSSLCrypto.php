<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class NoWeakSSLCrypto extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        $this->atomFunctionIs(array('\\stream_socket_enable_crypto'))
             ->outWithRank('ARGUMENT', 2)
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs(array('\STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT',
                                  '\STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT',
                                  '\STREAM_CRYPTO_METHOD_SSLv2_CLIENT',
                                  '\STREAM_CRYPTO_METHOD_SSLv3_CLIENT',
                                  '\STREAM_CRYPTO_METHOD_SSLv23_CLIENT',
                                  '\STREAM_CRYPTO_METHOD_SSLv2_SERVER',
                                  '\STREAM_CRYPTO_METHOD_SSLv3_SERVER',
                                  '\STREAM_CRYPTO_METHOD_SSLv23_SERVER',
                                  ), self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // curl_septop() with the following constants
        $this->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs(array('\CURL_SSLVERSION_TLSv1',
                                  '\CURL_SSLVERSION_SSLv2',
                                  '\CURL_SSLVERSION_SSLv3',
                                  '\CURL_SSLVERSION_TLSv1_0',
                                  '\CURL_SSLVERSION_SSLv2',
                                  ));
        $this->prepareQuery();

        // This is for fsockopen and co.
        $this->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '^(sslv2|sslv3)'); // tls is for v1.0 to v1.3
        $this->prepareQuery();
    }
}

?>
