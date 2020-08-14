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


namespace Exakat\Analyzer\Interfaces;

use Exakat\Analyzer\Analyzer;

class IsExtInterface extends Analyzer {
    public function analyze(): void {
        $exts = $this->rulesets->listAllAnalyzer('Extensions');

        $interfaces = array($this->loadIni('php_interfaces.ini', 'interfaces'));
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
            $ini = $this->load($inifile, 'interfaces');

            if (!empty($ini[0])) {
                $interfaces[] = $ini;
            }
        }

        $interfaces = array_merge(...$interfaces);
        if (empty($interfaces)) {
            return;
        }

        $interfaces = makeFullNsPath($interfaces);

        $this->atomIs('Class')
             ->outIs(array('IMPLEMENTS', 'EXTENDS'))
             ->fullnspathIs($interfaces);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspathIs($interfaces);
        $this->prepareQuery();

        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIs($interfaces);
        $this->prepareQuery();

        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->fullnspathIs($interfaces);
        $this->prepareQuery();
    }
}

?>
