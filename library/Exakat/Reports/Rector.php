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


namespace Exakat\Reports;

use Exakat\Exakat;

class Rector extends Reports {
    const FILE_EXTENSION = 'yaml';
    const FILE_FILENAME  = 'rector.exakat';

    private $matches = array('Php/IsAWithString'                   => 'Rector\CodeQuality\Rector\FuncCall\IsAWithStringWithThirdArgumentRector',
                             'Structures/ShouldPreprocess'         => 'Rector\CodeQuality\Rector\Concat\JoinStringConcatRector',
                             'Structures/ElseIfElseif'             => 'Rector\CodeQuality\Rector\If_\ShortenElseIfR',
                             'Structures/CouldUseShortAssignation' => 'Rector\CodeQuality\Rector\Assign\CombinedAssignRector',
                             'Structures/AddZero'                  => 'Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector',
                             'Structures/MultiplyByOne'            => 'Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector',
                             'Arrays/MultipleIdenticalKeys'        => 'Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector',
                             'Structures/MultipleDefinedCase'      => 'Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector',
                             'Functions/NeverUsedParameter'        => 'Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParameterRector',
                             'Structures/NoChoice'                 => 'Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector',
                             'Functions/Closure2String'            => 'Rector\CodingStyle\Rector\FuncCall\SimpleArrayCallableToStringRector',
                             'Php/DectectCurrentClass'             => 'Rector\Php74\Rector\Class_\ClassConstantToSelfClassRector',

                            );

    public function dependsOnAnalysis(): array {
        return array('Rector',
                     );
    }

    protected function _generate(array $analyzerList): string {
        $themed = $this->rulesets->getRulesetsAnalyzers($this->dependsOnAnalysis());

        $analysis = $this->dump->fetchAnalysersCounts($themed);
        $analysis = array_filter($analysis->toHash('analyzer', 'count'), function ($x) { return $x >= 1;});

        $services = array();
        foreach($analysis as $analyzer => $count) {
            $services[] = $this->matches[$analyzer];
        }
        $this->count(count($services));

        $date = date('Y-m-d h:i:j');
        $version = Exakat::VERSION . '- build ' . Exakat::BUILD;

        // preparing the list of PHP extensions to compile PHP with
        $return = <<<YAML
# Add this to your rector.yaml file
# At the root of the source to be analyzed
# Generated on $date, by Exakat ($version)

services:
    
YAML
. implode("\n    ", $services) . "\n";

        return $return;
    }
}

?>