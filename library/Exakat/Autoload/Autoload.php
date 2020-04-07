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

namespace Exakat\Autoload;

include __DIR__ . '/Autoloader.php';

class Autoload implements Autoloader {
    public function autoload($name) {
        $file = dirname(__DIR__, 2) . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_test($name) {
        $file = dirname(__DIR__, 3) . '/tests/analyzer/' . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_phpunit($name) {
        $fileName = preg_replace('/^([^_]+?)_(.*)$/', '$1' . DIRECTORY_SEPARATOR . '$2', $name);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName);
        $file = dirname(__DIR__, 3) . "/tests/analyzer/{$fileName}.php";

        if (file_exists($file)) {
            include $file;
        }
    }

    public function registerAutoload() {
        spl_autoload_register(array(self::class, 'autoload'));
    }
}

?>