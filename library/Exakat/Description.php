<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat;

use Exakat\Config;

class Description {
    private $language = 'en';
    private $ini = array('description'    => '',
                         'name'           => '',
                         'clearphp'       => '');
    private $analyzer = null;

    public function __construct($analyzer) {
        $config = Config::factory();
        $this->analyzer = $analyzer;

        $filename = $config->dir_root.'/human/'.$this->language.'/'.str_replace('\\', '/', str_replace('Exakat\\Analyzer\\', '', $analyzer)).'.ini';

        if (file_exists($filename)) {
            $this->ini = parse_ini_file($filename) + $this->ini;
        }
        
        assert(isset($this->ini['description']), 'Missing description in '.$analyzer);
        assert(isset($this->ini['name']), 'Missing name in '.$analyzer);
        assert(isset($this->ini['exakatSince']), 'Missing exakatSince in '.$analyzer);
        assert(isset($this->ini['clearphp']), 'Missing clearphp in '.$analyzer);

        // else is the default values already defined above
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function getDescription() {
        return $this->ini['description'];
    }

    public function getName() {
        return $this->ini['name'];
    }

    public function getClearPHP() {
        return $this->ini['clearphp'];
    }

    public function getVersionAdded() {
        return $this->ini['exakatSince'];
    }
}

?>
