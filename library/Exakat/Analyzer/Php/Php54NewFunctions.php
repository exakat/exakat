<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class Php54NewFunctions extends FunctionDefinition {
    protected $phpVersion = '5.3-';
    
    public function analyze() {
        $this->functions = array(
'hex2bin',
'http_response_code',
'get_declared_traits',
'getimagesizefromstring',
'stream_set_chunk_size',
'socket_import_stream',
'trait_exists',
'header_register_callback',
'class_uses',
'session_status',
'session_register_shutdown',
'mysqli_error_list',
'mysqli_stmt_error_list',
'libxml_set_external_entity_loader',
'ldap_control_paged_result',
'ldap_control_paged_result_response',
'transliterator_create',
'transliterator_create_from_rules',
'transliterator_create_inverse',
'transliterator_get_error_code',
'transliterator_get_error_message',
'transliterator_list_ids',
'transliterator_transliterate',
'zlib_decode',
'zlib_encode',
);
        parent::analyze();
    }
}

?>
