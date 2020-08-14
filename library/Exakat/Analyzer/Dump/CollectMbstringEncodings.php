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

namespace Exakat\Analyzer\Dump;

class CollectMbstringEncodings extends AnalyzerArrayHashResults {
    protected $analyzerName = 'Mbstring Encodings';

    public function analyze(): void {
        // mb_stotolower('PHP', 'utf-8');
        $encodings = $this->loadIni('mbstring_encodings.ini', 'encodings');

        $this->atomIs(self::STRINGS_LITERALS)
             ->noDelimiterIs($encodings, self::CASE_INSENSITIVE)
             ->values('noDelimiter');
        $encodings = $this->rawQuery()->toArray();

        $stats = array_count_values($encodings);

        $valuesSQL = array();
        foreach($stats as $name => $count) {
            $valuesSQL[] = array($name, $count);
        }

        if (empty($valuesSQL)) {
            return;
        }

        $this->analyzerValues = $valuesSQL;

        $this->prepareQuery();
    }
}

?>
