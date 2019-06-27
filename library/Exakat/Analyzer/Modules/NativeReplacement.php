<?php
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

namespace Exakat\Analyzer\Modules;

use Exakat\Analyzer\Analyzer;

class NativeReplacement extends Analyzer {
    protected $replacements = array();

    public function analyze() {
        $this->replacements = $this->loadJson('native_replacement.json');

        if (empty($this->replacements)) {
            return;
        }
        
        if (isset($this->replacements->variables)) {
            $variables = $this->replacements->variables;
            
            $this->atomIs(array('Variable', 'Variableobject', 'Variablearray', 'Phpvariable'))
                  ->codeIs(array_keys((array) $variables));
            $this->prepareQuery();
        }

        if (isset($this->replacements->functions)) {
            $functions = $this->replacements->functions;
            $functions = makeFullnspath(array_keys((array) $functions));
            
            $this->atomFunctionIs($functions);
            $this->prepareQuery();
        }

        if (isset($this->replacements->classes)) {
            $classes = $this->replacements->classes;
            $classes = makeFullnspath(array_keys((array) $classes));
            
            $this->atomIs('Class')
                 ->fullnspathIs($classes);
            $this->prepareQuery();
        }
    }
}

?>
