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
namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class MiddleVersion extends Analyzer {
    private $bugfixes = array();

    public function dependsOn(): array {
        $this->bugfixes = $this->methods->getBugFixes();

        $depends = array();
        foreach($this->bugfixes as $bugfix) {
            if (!empty($bugfix['analyzer'])) {
                $depends[] = $bugfix['analyzer'];
            }
        }

        return $depends;
    }

    public function analyze(): void {
        // bugfixes based on functions
        $functions = array();
        foreach($this->bugfixes as $bugfix) {
            if (!empty($bugfix['function'])) {
                $functions[] = $bugfix['function'];
            }
        }
        $this->atomFunctionIs(makeFullNsPath($functions));
        $this->prepareQuery();

        // bugfixes based on analyzers
        $analyzers = array_column($this->bugfixes, 'analyzer');
        $available = array_filter($analyzers);

        $this->analyzerIs($available)
             ->analyzerIsNot('self')
             ->ignore();
        $this->prepareQuery();
    }
}

?>
