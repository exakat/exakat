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


namespace Report\Content\Directives;

class Ob extends Directives {
    public function __construct() {
        $this->name         = 'Ob';
        
        if ($this->checkPresence('Extensions\\Extob')) {
            $this->directives[] = array('name' => 'output_buffering',
                                       'suggested' => '4096', 
                                       'documentation' => 'You can enable output buffering for all files by setting this directive to \'On\'. If you wish to limit the size of the buffer to a certain size - you can use a maximum number of bytes instead of \'On\', as a value for this directive (e.g., output_buffering=4096). As of PHP 4.3.5, this directive is always Off in PHP-CLI.');

            $this->directives[] = array('name' => 'output_handler',
                                       'suggested' => ' mb_output_handler or ob_iconv_handler(); ob_gzhandler() or zlib.output_compression;', 
                                       'documentation' => 'Use the first suggested values to handle character encoding. Use the second values for on the fly compression; Use your own function if you have one.');

            $this->directives[] = array('name' => 'implicit_flush',
                                       'suggested' => 'False', 
                                       'documentation' => 'Changing this to TRUE tells PHP to tell the output layer to flush itself automatically after every output block : this has performances penalty.');

        }
    }
}

?>