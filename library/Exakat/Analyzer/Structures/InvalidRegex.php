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

class InvalidRegex extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        $functionList = makeFullnspath(UnknownPregOption::$functions);

        $this->atomFunctionIs($functionList)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             ->raw(<<<'GREMLIN'
map{
     if (it.get().value("delimiter") == "'") {
       regex = it.get().value('noDelimiter').replaceAll("\\\\([\$'\\\\])", "\$1");
     } else {
       regex = it.get().value('noDelimiter').replaceAll('\\\\([\$"\\\\])', "\$1");
     }
     
     [regex, it.get().value('fullcode')]
}

GREMLIN
);
        $regexSimple = $this->rawQuery();

        $this->atomFunctionIs($functionList)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Concatenation'), self::WITH_CONSTANTS)
             ->hasOut('CONCAT')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outWithRank('ARGUMENT', 0)
                             ->atomIs(array('String', 'Identifier', 'Nsname', 'Staticconstant'))
                     )
             )
             ->not(
                $this->side()
                     ->outIs('CONCAT')
                     ->outIs('CONCAT')
             )
             ->raw(<<<'GREMLIN'
 where( 
    __.sideEffect{ c = it.get().value("count") - 1;}
      .out("CONCAT")
      .filter{ it.get().value("rank") == c;}
      .hasLabel("String", "Identifier", "Nsname", "Staticconstant")
      .not(where(__.out("CONCAT")))
)
.not( where( __.out("CONCAT").hasLabel("String", "Identifier", "Nsname", "Staticconstant").not(has("noDelimiter"))) )
.where( __.sideEffect{ liste = [];}
          .out("CONCAT").order().by('rank')
          .hasLabel("String", "Variable", "Array", "Functioncall", "Methodcall", "Staticmethodcall", "Member", "Staticproperty", "Identifier", "Nsname", "Staticconstant", 'Parenthesis')
          .sideEffect{ 
               if ('noDelimiter' in it.get().keys()) {
                   liste.add(it.get().value("noDelimiter").toString().replaceAll('\\\\([\$\\\'"\\\\])', "\$1"));
                } else {
                   liste.add("smi"); // smi is compatible with flags
                }
          }
          .fold()
)
.map{[liste.join(''), it.get().value('fullcode')]};
GREMLIN
);
        $regexComplex = $this->rawQuery();

        $regexList = array_merge($regexSimple->toArray(), $regexComplex->toArray());

        $invalid = array();
        foreach($regexList as list($regex, $fullcode)) {
            // @ is important here : we want preg_match to fail silently.
            if (false === @preg_match($regex, '')) {
                $invalid[] = $fullcode;
            }
        }

        if (empty($invalid)) {
            return;
        }

        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_ALL, self::WITH_CONSTANTS)
             ->fullcodeIs($invalid)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
