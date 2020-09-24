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

class MissingNew extends Analyzer {
    public function dependsOn(): array {
        return array('Functions/IsExtFunction',
                     'Constants/IsExtConstant',
                    );
    }

    public function analyze(): void {
        $this->atomIs(self::CIT)
             ->values('fullnspath')
             ->unique();
        $customClasses = $this->rawQuery();

        $phpClasses = $this->loadIni('php_classes.ini', 'classes');

        $classes = array_unique(array_merge($phpClasses, $customClasses->toArray()));
        $classes = makeFullnspath($classes);

        // $a = file();
        $this->atomIs('Functioncall')
             ->analyzerIsNot('Functions/IsExtFunction')
             ->tokenIs(self::STATICCALL_TOKEN)
             ->hasNoFunctionDefinition()
             ->fullnspathIs($classes, self::CASE_INSENSITIVE);
        $this->prepareQuery();

        // $a = C;
        $this->atomIs(self::STATIC_NAMES)
             ->analyzerIsNot('Constants/IsExtConstant')
             ->hasNoConstantDefinition()
             ->hasNoIn(array('CLASS', 'IMPLEMENTS', 'EXTENDS', 'TYPEHINT', 'RETURNTYPE', 'NAME')) // NAME is to skip const x = 1
             ->isNot('isStub', true)
             ->fullnspathIs($classes, self::CASE_INSENSITIVE);
        $this->prepareQuery();
    }
}

?>
