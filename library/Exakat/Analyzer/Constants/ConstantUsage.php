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

class ConstantUsage extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // Nsname that is not used somewhere else
        $this->atomIs('Nsname')
             ->hasNoIn(array('NEW', 'USE', 'NAME', 'EXTENDS', 'IMPLEMENTS', 'CLASS', 'CONST', 'TYPEHINT', 'RETURNTYPE',
                             'FUNCTION', 'GROUPUSE'));
        $this->prepareQuery();

        // Identifier that is not used somewhere else
        $this->atomIs('Identifier')
             ->hasNoIn(array('NEW', 'USE', 'NAME', 'CONSTANT', 'MEMBER', 'TYPEHINT', 'INSTEADOF', 'METHOD', 'TYPEHINT', 'RETURNTYPE',
                             'CLASS', 'EXTENDS', 'IMPLEMENTS', 'CLASS', 'AS', 'VARIABLE', 'FUNCTION', 'CONST', 'GROUPUSE'));
        $this->prepareQuery();

        // special case for Boolean and Null
        $this->atomIs(array('Boolean', 'Null'));
        $this->prepareQuery();

        // defined('constant') : then the string is a constant
        $this->atomFunctionIs(array('\defined', '\constant'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS);
        $this->prepareQuery();

        // Const outside a class
    }
}

?>
