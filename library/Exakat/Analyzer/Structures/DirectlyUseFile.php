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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DirectlyUseFile extends Analyzer {
    public function analyze() {
        // md5(file_get_contents('path));
        $functions = array(0 => array('\\md5',
                                      '\\highlight_string',
                                      '\\parsekit_compile_string',
                                      '\\parse_ini_string',
                                      '\\sha1',
                                      '\\simplexml_load_string',
                                      '\\yaml_parse',
                                     ),
                           1 => array('\\hash',
                                      '\\hash_hmac_file',
                                      '\\hash_update',
                                      '\\recode',
                                      '\\recode_string',
                                     ),
                           );

/*
        This works with file_put_contents()
                                      '\\openssl_pkcs12_export',
                                      '\\openssl_pkcs12_export',
                                      '\\openssl_pkey_export',
                                      '\\openssl_x509_export'
*/
        
        foreach($functions as $position => $function) {
            $this->atomFunctionIs($function)
                 ->outWithRank('ARGUMENT', $position)
                 ->functioncallIs('\\file_get_contents')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
