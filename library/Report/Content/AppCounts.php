<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Content;

class AppCounts extends \Report\Content {
    protected $hash = array();

    public function collect() {
        // Which extension are being used ? 
        $extensions = array(
                    'Summary' => array(
                            'Namespaces'     => 'Namespace',
                            'Classes'        => 'Class',
                            'Interfaces'     => 'Interface',
                            'Trait'          => 'Trait',
                            'Function'       => array('index' => 'Function', 'Below' => 'filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}'),
                            'Variables'      => array('index' => 'Variable', 'Unique' => 'code'),
                            'Constants'      => 'Constants\\Constantnames',
                     ),
                    'Classes' => array(
                            'Classes'        => 'Class',
                            'Class constants'=> array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Const")'),
                            'Properties'     => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{!it.out("STATIC").any()}.out("DEFINE")'),
                            'Static properties' => array('index' => 'Class', 
                                                         'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{it.out("STATIC").any()}.out("DEFINE")'),
                            'Methods'        => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Function").filter{!it.out("STATIC").any()}'),
                            'Static methods' => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("STATIC").any()}'),
                     ),
                    'Structures' => array(
                            'Ifthen'        => 'Ifthen',
                            'Switch'        => 'Switch',
                            'Case'          => 'Case',
                            'Default'       => 'Default',
                            'For'           => 'For',
                            'Foreach'       => 'Foreach',
                            'While'         => 'While',
                            'Do..while'     => 'Dowhile',
                            'New'           => 'New',
                            'Clone'         => 'Clone',
                            'Throw'         => 'Throw',
                            'Try'           => 'Try',
                            'Catch'         => 'Catch',
                            'Finally'       => 'Finally',
                            'Yield'         => 'Yield',
                            '?  :'          => 'Ternary',
                            'Variables constants' => 'Constants\\VariableConstants',
                            'Variables variables' => 'Variables\\VariableVariable',
                            'Variables functions' => 'Functions\\Dynamiccall',
                            'Variables classes' => 'Classes\\VariableClasses',
                            
                     ),
                    );

        foreach($extensions as $section => $hash) {
            $this->hash[$section] = array();
            foreach($hash as $name => $ext) {
                if (is_string($ext)) {
                    if (strpos($ext, '\\') === false) {
                        $queryTemplate = "g.idx('atoms')[['atom':'$ext']].count()"; 
                    } else {
                        $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $ext)."']].out('ANALYZED').count()"; 
                    }
                } elseif (isset($ext['Unique'])) {
                    $queryTemplate = "g.idx('atoms')[['atom':'{$ext['index']}']].{$ext['Unique']}.unique().count()"; 
                } elseif (isset($ext['Below'])) {
                    $queryTemplate = "g.idx('atoms')[['atom':'{$ext['index']}']].{$ext['Below']}.count()"; 
                } else {
                    $queryTemplate = "g.idx('atoms')[['atom':'{$ext['index']}']].count()"; 
                }
                $vertices = $this->query($queryTemplate);
                $v = $vertices[0][0];
                $this->hash[$section][$name] = $v;
                continue;
            }
        }
    }
}

?>
