<?php

namespace Analyzer\Php;

use Analyzer;

class Php56NewFunctions extends Analyzer\Common\FunctionDefinition {
    protected $phpversion = "5.6-";
    
    public function analyze() {
        $this->functions = array(
'gmp_root',
'gmp_rootrem',
'ldap_escape',
'oci_get_implicit_resultset',
'openssl_x509_fingerprint',
'openssl_spki_new',
'openssl_spki_verify',
'openssl_spki_export_challenge',
'openssl_spki_export',

);
        parent::analyze();
    }
}

?>