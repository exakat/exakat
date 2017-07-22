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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\FunctionDefinition;

class Php72NewFunctions extends FunctionDefinition {
    protected $phpVersion = '7.2-';
    
    public function analyze() {
        $this->functions = array( 'mb_ord',
                                  'mb_chr',
                                  'mb_scrub',
                                  'stream_isatty',
                                  'sapi_windows_vt100_support',
                                  'imagesetclip',
                                  'imagegetclip',
                                  'imageopenpolygon',
                                  'imageresolution',
                                  'imagecreatefrombmp', 
                                  'imagebmp',
                                  'oci_register_taf_callback',
                                  'oci_disable_taf_callback',
                                  'socket_addrinfo_lookup',
                                  'socket_addrinfo_connect',
                                  'socket_addrinfo_bind',
                                  'socket_addrinfo_explain',
                                );
        parent::analyze();
    }
}

?>
