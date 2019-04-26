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

namespace Exakat\Autoload;

use Exakat\Config;
use Phar;

class AutoloadDev {
    private $path = '';
    
    public function __construct($path) {
        if (phar::running()) {
            // No autoloadDev with phar
            // Ignoring it all
            return;
        }
        
        $this->path = $path;
    }

    public function autoload_dev($name) {
        if (empty($this->path)) { return; }

        $fileName = str_replace('Exakat\\', '', $name);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName);
        $file = "{$fileName}.php";

        $fullPath = "{$this->path}/$file";
        if (file_exists($fullPath)) {
            include $fullPath;

            return;
        }
    }

    public function registerAutoload() {
        spl_autoload_register(array($this, 'autoload_dev'));
    }

    public function getAllAnalyzers() {
        $fullPath = "{$this->path}/Analyzer/analyzers.ini";

        if (!file_exists($fullPath)) {
            return array();
        }

        $ini = parse_ini_file($fullPath);

        return $ini ?? array();
    }
}

?>