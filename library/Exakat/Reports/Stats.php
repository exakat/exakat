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


class Stats extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'exakat.stat';

    private $extensions = array(
                'Summary' => array(
                        'Namespaces'     => 'Namespace',
                        'Classes'        => 'Class',
                        'Interfaces'     => 'Interface',
                        'Trait'          => 'Trait',
                        'Functions'      => 'Function',
                        'Variables'      => 'Variabledefinition',
                        'Constants'      => 'Constants/Constantnames',
                 ),
                'Classes' => array(
                        'Classes'           => 'Class',
                        'Class constants'   => 'Classes/ConstantDefinition',
                        'Properties'        => 'Propertydefinition',
                        'Methods'           => 'Method',
                        // Spot Abstract methods
                        // Spot Final Methods
                 ),
                'Structures' => array(
                        'Ifthen'              => 'Ifthen',
                        'Else'                => 'Structures/ElseUsage',
                        'Switch'              => 'Switch',
                        'Match'               => 'Match',
                        'Case'                => 'Case',
                        'Default'             => 'Default',
                        'Fallthrough'         => 'Structures/Fallthrough',
                        'For'                 => 'For',
                        'Foreach'             => 'Foreach',
                        'While'               => 'While',
                        'Do..while'           => 'Dowhile',

                        'New'                 => 'New',
                        'Clone'               => 'Clone',
                        'Class constant call' => 'Staticconstant',
                        'Method call'         => 'Methodcall',
                        'Static method call'  => 'Staticmethodcall',
                        'Properties usage'    => 'Property',
                        'Static property'     => 'Staticproperty',

                        'Throw'               => 'Throw',
                        'Try'                 => 'Try',
                        'Catch'               => 'Catch',
                        'Finally'             => 'Finally',

                        'Yield'               => 'Yield',
                        'Yield From'          => 'Yieldfrom',

                        '?  :'                => 'Ternary',
                        '?: '                 => 'Php/Coalesce',
                        '?? '                 => 'Php/Coalesce',

                        'Variables constants' => 'Constants/VariableConstant',
                        'Variables variables' => 'Variables/VariableVariables',
                        'Variables functions' => 'Functions/Dynamiccall',
                        'Variables classes'   => 'Classes/VariableClasses',
                ),
            );


    public function _generate(array $analyzerList): string {

        $analyzerList = array_merge(...array_values($this->extensions));
        $analyzerList = array_filter($analyzerList, function ($x) { return strpos($x, '/') !== false; });

        $res = $this->dump->fetchTable('atomsCounts');
        $atoms = $res->toHash('atom', 'count');

        $res = $this->dump->fetchAnalysersCounts($analyzerList);
        $atoms = array_merge($atoms, $res->toHash('analyzer', 'count'));
        $this->count(count($atoms));

        $results = $this->extensions;
        foreach($results as &$analyzers) {
            foreach($analyzers as $name => &$analyzer) {
                $analyzer = $atoms[$analyzer] ?? 0;
            }
        }

        return json_encode($results);
    }

    public function dependsOnAnalysis(): array {
        return array('Stats',
                     );
    }

}

?>