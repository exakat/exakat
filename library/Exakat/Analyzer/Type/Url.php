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


namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Dump\AnalyzerResults;

class Url extends AnalyzerResults {
    protected $analyzerName = 'Url';

    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // 'http://www.exakat.io/'
        $this->atomIs(self::STRINGS_ALL, self::WITHOUT_CONSTANTS)
             ->hasNoIn('CONCAT')
             ->has('noDelimiter')
             ->raw('has("noDelimiter", containing("://"))')
             ->regexIs('noDelimiter', '^.?([a-z]+)://[-\\\\p{L}0-9+&@#/%?=~_|!:,.;]*[-a-zA-Z0-9+&@#/%=~_|].?\\$')
             ->toResults();
        $this->prepareQuery();
    }
}

?>
