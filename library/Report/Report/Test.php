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


namespace Report\Report;

class Test extends Premier {
    public function __construct($project, $client) {
        parent::__construct($project, $client);
    }

    public function prepare() {
/////////////////////////////////////////////////////////////////////////////////////
/// Compilations
/////////////////////////////////////////////////////////////////////////////////////

        $this->createLevel1('Compilation');
        $this->addContent('Text', 'This table is a summary of compilation situation. Every PHP script has been tested for compilation with the mentionned versions. Any error that was found is displayed, along with the kind of messsages and the list of erroneous files.');
        $this->createLevel2('Compile');
        $config = \Config::factory();

        $compilations = new \Report\Content\Compilations($this->client);
        $compilations->setVersions($config->other_php_versions);
        $this->addContent('Compilations', $compilations);

        foreach($config->other_php_versions as $code) {
            $version = substr($code, 0, 1).'.'.substr($code, 1);
            $this->createLevel2('Compatibility '.$version);
            $this->addContent('Text', 'This is a summary of the compatibility of the code with PHP '.$version.'. Those are the code syntax and structures that are used in the code, and that are incompatible with PHP '.$version.'. You must remove them before moving to this version.');
            $this->addContent('Compatibility', 'Compatibility'.$code);
        }

        
        return true;
    }
}

?>
