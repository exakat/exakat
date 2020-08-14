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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class CustomConstantUsage extends Analyzer {
    public function dependsOn(): array {
        return array('Constants/ConstantUsage',
                    );
    }

    public function analyze(): void {
        $exts = $this->rulesets->listAllAnalyzer('Extensions');

        $constants = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
            $ini = $this->load($inifile, 'constants');

            if (!empty($ini[0])) {
                $constants[] = $ini;
            }
        }

        $constants = array_merge(...$constants);
        if (empty($constants)) {
            return;
        }

        $constants = makeFullNsPath($constants);

        // @note NSnamed are OK by default (may be not always!)
        $this->atomIs(self::CONSTANTS_ALL)
             ->analyzerIs('Constants/ConstantUsage')
             ->fullnspathIsNot($constants)
             ->inIs('DEFINITION')
             ->atomIs(array('Constant', 'Defineconstant'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
