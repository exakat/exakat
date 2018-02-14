<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    const LANGUAGES = ['en'];

    private $language = 'en';
    private $analyzer = '';
    private $configPath = '';
    private $ini = array();

    public function __construct($analyzerName, $configPath, $language = 'en') {
        $this->analyzer = $analyzerName;
        $this->configPath = $configPath;
        $this->language = in_array($language, self::LANGUAGES) ? $language : 'en';

        $filename = "$this->configPath/human/$this->language/$this->analyzer.ini";

        assert(file_exists($filename), "Documentation for '$analyzerName' doesn't exists : $filename.");
        
        $this->ini = parse_ini_file($filename);
        assert($this->ini !== null, "Documentation for '$analyzerName' doesn't exists : $filename.");
        
        assert(isset($this->ini['description']), 'Missing description in '.$analyzerName);
        assert(isset($this->ini['name']), 'Missing name in '.$analyzerName);
        assert(isset($this->ini['exakatSince']), 'Missing exakatSince in '.$analyzerName);
        assert(isset($this->ini['clearphp']), 'Missing clearphp in '.$analyzerName);

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
