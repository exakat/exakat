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


namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Exakat;

class Phpcsfixer extends Reports {
    const FILE_EXTENSION = 'php';
    const FILE_FILENAME  = 'phpcsfixer.exakat';
    
    private $matches = array(   'Php/IsnullVsEqualNull'         => 'is_null',
                                'Php/NewExponent'               => 'pow_to_exponentiation',
                                'Php/LogicalInLetters'          => 'logical_operators',
                                'Structures/UseConstant'        => 'function_to_constant',
                                'Structures/ElseIfElseif'       => 'elseif',
                                'Structures/PHP7Dirname'        => 'combine_nested_dirname',
                                'Structures/CouldUseDir'        => 'dir_constant',
                                'Php/IssetMultipleArgs'         => 'combine_consecutive_issets',
                                'Classes/DontUnsetProperties'   => 'no_unset_on_property',
                                'Structures/MultipleUnset'      => 'combine_consecutive_unsets',
                                'Php/ImplodeOneArg'             => 'implode_call',
                            );

    public function dependsOnAnalysis() : array {
        return array('php-cs-fixable',
                     );
    }

    protected function _generate($analyzerList) {
        $themed = $this->rulesets->getRulesetsAnalyzers($this->dependsOnAnalysis());

        $res = $this->sqlite->query('SELECT analyzer FROM resultsCounts WHERE analyzer IN (' . makeList($themed) . ') AND count >= 1');
        $rules = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $name = "'" . $this->matches[$row['analyzer']] . "'";
            $rules[] = sprintf('            %- 30s => true,', $name);
            $this->count();
        }
        natcasesort($rules);
        
        $date = date('Y-m-d h:i:j');
        $version = Exakat::VERSION . '- build ' . Exakat::BUILD;
        
        $rules = implode(PHP_EOL, $rules);

        // preparing the list of PHP extensions to compile PHP with
        $return = <<<PHP
<?php

/**
  * Add this to your .php_cs file
  * At the root of the source to be analyzed
  * Generated on $date, by Exakat ($version)
*/

// Adapt this to your directory structure
\$finder = PhpCsFixer\Finder::create()
                            ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules(
        array(
{$rules}
        )
    )
    ->setFinder(\$finder);

PHP;

        return $return;
    }
}

?>