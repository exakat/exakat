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

class NoHardcodedHash extends Analyzer {
    public function analyze() {
        $algos = $this->loadJson('hash_length.json');
        $stopwords = $this->loadIni('NotHash.ini', 'ignore');
        
        $regexDate = '^\\\\d{4}(0?[1-9]|1[012])(0?[1-9]|[12][0-9]|3[01])\$';
        
        // Find common hashes, based on hexadecimal and length
        foreach(array_keys((array) $algos) as $size) {
            $this->atomIs('String')
                 ->hasNoOut('CONCAT')
                 ->regexIs('noDelimiter', '^[a-fA-Z0-9]{'.$size.'}\\$')
                 ->isNotMixedcase('noDelimiter')
                 ->noDelimiterIsNot($stopwords)
                 ->regexIsNot('noDelimiter', $regexDate)
                 ->not(
                    $this->side()
                         ->filter(
                            $this->side()
                                 ->inIs('ARGUMENT')
                                 ->atomIs('Functioncall')
                                 ->fullnspathIs(array('\\hexdec',
                                                      '\\hex2bin',
                                                      '\\intval',
                                                      '\\decbin',
                                                      '\\bindec',
                                                      '\\dechex',
                                                      '\\decoct',
                                                      '\\octdec',
                                                      )))
                  );
            $this->prepareQuery();
        }
        
        // Crypt (some salts are missing)
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '^\\\\\\$(1|2a|2x|2y|5|6)\\\\\\$.+')
             ->noDelimiterIsNot($stopwords)
             ->regexIsNot('noDelimiter', $regexDate);
        $this->prepareQuery();

        // Base64 encode
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '^[a-zA-Z0-9]+={0,2}\\$')
             ->raw('filter{ it.get().value("noDelimiter").length() % 4 == 0}')
             ->isNotMixedcase('noDelimiter')
             ->codeLength(' > 101');
        $this->prepareQuery();
    }
}

?>
