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


namespace Report\Content\Directives;

class Standard extends Directives {
    public function __construct() {
        $this->name         = 'Standard';
        $this->hasDirective = self::ON;

        $this->directives[] = array('name'          => 'memory_limit',
                                    'suggested'     => '120',
                                    'documentation' => 'This sets the maximum amount of memory in bytes that a script is allowed to allocate. This helps prevent poorly written scripts for eating up all available memory on a server. It is recommended to set this as low as possible and avoid removing the limit.');

        $this->directives[] = array('name'          => 'max_execution_time',
                                    'suggested'     => '90',
                                    'documentation' => 'This sets the maximum amount of time, in seconds, that a script is allowed to run. The lower the value, the better for the server, but also, the better has the script to be written. Avoid really large values that are only useful for admin, and set them per directory.');

        $this->directives[] = array('name'          => 'expose_php',
                                    'suggested'     => 'Off',
                                    'documentation' => 'Exposes to the world that PHP is installed on the server. For security reasons, it is better to keep this hidden.');

        $this->directives[] = array('name'          => 'display_errors',
                                    'suggested'     => 'Off',
                                    'documentation' => 'This determines whether errors should be printed to the screen as part of the output or if they should be hidden from the user.');

        $this->directives[] = array('name'          => 'error_reporting',
                                    'suggested'     => 'E_ALL',
                                    'documentation' => 'Set the error reporting level. Always set this high, so as to have the errors reported, and logged.');

        $this->directives[] = array('name'          => 'log_errors',
                                    'suggested'     => 'On',
                                    'documentation' => 'Always log errors for future use');

        $this->directives[] = array('name'          => 'error_log',
                                    'suggested'     => 'Name of a writable file, suitable for logging.',
                                    'documentation' => 'Name of the file where script errors should be logged. ');

        $this->directives[] = array('name'          => 'realpath_cache_size',
                                    'suggested'     => '128k',
                                    'documentation' => 'Determines the size of the realpath cache to be used by PHP. The default value of "16k" is usually too low for modern application that open many files (autoload, fopen, filet_get_contents...). It is recommended to make this value up to 128 to 256k, and reduce it by testing with realpath_cache_get().');

        $this->directives[] = array('name'          => 'realpath_cache_ttl',
                                    'suggested'     => '3600',
                                    'documentation' => 'Duration of time (in seconds) for which to cache realpath information for a given file or directory. If the application\'s code doesn\'t change too often, you may set this directive to 3600 (one hour) or even more.');

        $this->directives[] = $this->extraConfiguration($this->name, 'info');
    }
}

?>