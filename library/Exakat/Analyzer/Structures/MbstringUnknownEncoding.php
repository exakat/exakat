<?php declare(strict_types = 1);
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
use Exakat\Query\DSL\FollowParAs;

class MbstringUnknownEncoding extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        $encodings = $this->loadIni('mbstring_encodings.ini', 'encodings');

        $positions = array('\\mb_preferred_mime_name'  => array(0),
                           '\\mb_regex_encoding'       => array(0),

                           '\\mb_check_encoding'       => array(1),
                           '\\mb_chr'                  => array(1),
                           '\\mb_ord'                  => array(1),
                           '\\mb_scrub'                => array(1),
                           '\\mb_strlen'               => array(1),

                           '\\mb_convert_case'         => array(2),
                           '\\mb_convert_encoding'     => array(2),
                           '\\mb_convert_kana'         => array(2),
                           '\\mb_decode_numericentity' => array(2),
                           '\\mb_encode_numericentity' => array(2),
                           '\\mb_internal_encoding'    => array(2),
                           '\\mb_strcut'               => array(2),
                           '\\mb_strtolower'           => array(2),
                           '\\mb_strtoupper'           => array(2),
                           '\\mb_strwidth'             => array(2),
                           '\\mb_substr_count'         => array(2),

                           '\\mb_stripos'              => array(3),
                           '\\mb_stristr'              => array(3),
                           '\\mb_strpos'               => array(3),
                           '\\mb_strripos'             => array(3),
                           '\\mb_strrpos'              => array(3),
                           '\\mb_strrchr'              => array(3),
                           '\\mb_strrichr'             => array(3),
                           '\\mb_strstr'               => array(3),
                           '\\mb_substr'               => array(3),

                           '\\mb_strimwidth'           => array(4),
                          );

        //mb_check_encoding($x, 'UTF9');
        $this->atomFunctionIs(array_keys($positions))
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positions, 'fnp')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs(array('String', 'Concatenation', 'Integer', 'Boolean', 'Null'), self::WITH_CONSTANTS)
             ->noDelimiterIsNot($encodings, self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
