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

    public function analyze() {
        $encodings = $this->loadIni('mbstring_encodings.ini', 'encodings');

        $positions = array('\\mb_preferred_mime_name' => 0,
                           '\\mb_regex_encoding' => 0,

                           '\\mb_check_encoding'       => 1,
                           '\\mb_chr'                  => 1,
                           '\\mb_ord'                  => 1,
                           '\\mb_scrub'                => 1,
                           '\\mb_strlen'               => 1,

                           '\\mb_convert_case'         => 2,
                           '\\mb_convert_encoding'     => 2,
                           '\\mb_convert_kana'         => 2,
                           '\\mb_decode_numericentity' => 2,
                           '\\mb_encode_numericentity' => 2,
                           '\\mb_internal_encoding'    => 2,
                           '\\mb_strcut'               => 2,
                           '\\mb_strtolower'           => 2,
                           '\\mb_strtoupper'           => 2,
                           '\\mb_strwidth'             => 2,
                           '\\mb_substr_count'         => 2,

                           '\\mb_stripos'              => 3,
                           '\\mb_stristr'              => 3,
                           '\\mb_strpos'               => 3,
                           '\\mb_strripos'             => 3,
                           '\\mb_strrpos'              => 3,
                           '\\mb_strrchr'              => 3,
                           '\\mb_strrichr'             => 3,
                           '\\mb_strstr'               => 3,
                           '\\mb_substr'               => 3,

                           '\\mb_strimwidth' => 4,
                          );

        $this->atomFunctionIs(array_keys($positions))
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positions, 'fnp')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs(array('String', 'Concatenation'), self::WITH_CONSTANTS)
             ->noDelimiterIsNot($encodings, self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs(array_keys($positions))
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positions, 'fnp')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot(array('String', 'Concatenation'), self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
