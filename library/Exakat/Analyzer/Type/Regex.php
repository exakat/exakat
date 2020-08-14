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

class Regex extends AnalyzerResults {
    protected $analyzerName = 'Regex';

    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/CreateDefaultValues',
                    );
    }

    private $pregFunctions = array('\\preg_match_all',
                                   '\\preg_match',
                                   '\\preg_replace',
                                   '\\preg_replace_callback',
//                                   '\\preg_replace_callback_array',
                                   '\\mb_ereg_match',
                                   '\\mb_ereg_replace_callback',
                                   '\\mb_ereg_replace',
                                   '\\mb_ereg_search_getpos',
                                   '\\mb_ereg_search_getregs',
                                   '\\mb_ereg_search_init',
                                   '\\mb_ereg_search_pos',
                                   '\\mb_ereg_search_regs',
                                   '\\mb_ereg_search_setpos',
                                   '\\mb_ereg_search',
                                   '\\mb_ereg',
                                   '\\mb_eregi_replace',
                                   '\\mb_eregi',
                                   );

    public function analyze(): void {

        // preg_match('/a/', ...)
        $this->atomFunctionIs($this->pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->toResults();
        $this->prepareQuery();

        // preg_match(array(regex1, regex2))
        $this->atomFunctionIs($this->pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->outIs('ARGUMENT')
             ->outIsIE('VALUE')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->toResults();
        $this->prepareQuery();

        // preg_relace_callback_array(array(regex1 => callback, regex2))
        $this->atomFunctionIs('\\preg_replace_callback_array')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('INDEX')
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->toResults();
        $this->prepareQuery();
    }
}

?>
