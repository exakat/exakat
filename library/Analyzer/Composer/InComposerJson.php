<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Composer;

use Analyzer;

class InComposerJson extends Analyzer\Analyzer {
    public function analyze() {
        $config = \Config::factory();
        
        $composerFile = $config->codePath.'/composer.json';
        if (!file_exists($composerFile)) {
            return ;
        }

        $json = json_decode(file_get_contents($composerFile));
        // Not readable? Ignore.
        if ($json === null) {
            return ;
        }

        // Not present? Ignore.
        if (!isset($json->require)) {
            return ;
        }
        
        // Empty? Just nothing to do
        if (empty($json->require)) {
            return ;
        }
        
        $composer = new \Data\Composer();
        $c = array();
        foreach($json->require as $component => $version) {
            if (strpos($component, '/') === false) { continue; }
            $classes = $composer->getComposerClasses($component, $version);
            if (!empty($c)){
                $c[] = $this->makeFullNSPath($classes);
            }
        }
        if (!empty($c)) {
            $classes = array_merge(...$c);
        } else {
            $classes = array();
        }

        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs(array('Staticconstant', 'Staticproperty' ,'Staticmethodcall'))
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        // Interfaces
        $c = array();
        foreach($json->require as $component => $version) {
            if (strpos($component, '/') === false) { continue; }
            $interfaces = $composer->getComposerInterfaces($component, $version);
            if (!empty($c)){
                $c[] = $this->makeFullNSPath($interfaces);
            }
        }
        if (!empty($c)) {
            $interfaces = array_merge(...$c);
        } else {
            $interfaces = array();
        }

        $this->atomIs('Class')
             ->outIs('IMPLEMENTS')
             ->fullnspath($interfaces);
        $this->prepareQuery();

        $this->atomIs('Interface')
             ->outIs('IMPLEMENTS')
             ->fullnspath($interfaces);
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspath($interfaces);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->fullnspath($interfaces)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
