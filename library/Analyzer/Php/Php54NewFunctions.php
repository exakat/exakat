<?php

namespace Analyzer\Php;

use Analyzer;

class Php54NewFunctions extends Analyzer\Common\FunctionDefinition {
    protected $phpversion = "5.3-";
    
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