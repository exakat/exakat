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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class IsExtClass extends Analyzer {

    public function dependsOn(): array {
        return array('Classes/ClassUsage',
                    );
    }

    public function analyze(): void {
        // function foo() : \Generator {}
        $this->analyzerIs('Classes/ClassUsage')
             ->hasNoIn('DEFINITION')
             ->is('isExt', true);
        $this->prepareQuery();
        return;

        $exts = $this->rulesets->listAllAnalyzer('Extensions');

        $c = array($this->loadIni('php_classes.ini', 'classes'));
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
            $ini = $this->load($inifile, 'classes');

            if (!empty($ini[0])) {
                $c[] = $ini;
            }
        }

        $classes = array_merge(...$c);
        if (empty($classes)) {
            return;
        }

        $classes = makeFullNsPath($classes);
        $classes = array_keys(array_count_values($classes));

        $this->analyzerIs('Classes/ClassUsage')
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs('Usenamespace')
             ->outIs('USE')
             ->fullnspathIs($classes);
        $this->prepareQuery();
    }
}

?>
