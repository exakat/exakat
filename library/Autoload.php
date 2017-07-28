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

use Exakat\Config;

include 'helpers.php';

class Autoload {
    public static function autoload_library($name) {
        $file = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_test($name) {
        $path = dirname(__DIR__);

        $file = $path.'/tests/analyzer/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }

        $file = $path.'/tests/tokenizer/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_phpunit($name) {
        $fileName = preg_replace('/^([^_]+?)_(.*)$/', '$1'.DIRECTORY_SEPARATOR.'$2', $name);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName);
        $file = $fileName.'.php';

        if (file_exists($file)) {
            include $file;
        }
    }
}

spl_autoload_register('Autoload::autoload_library');
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    include __DIR__.'/../vendor/autoload.php';
}

$config = new Config($GLOBALS['argv']);
global $VERBOSE;
$VERBOSE = $config->verbose;
\Exakat\Analyzer\Analyzer::$staticConfig = $config;


?>