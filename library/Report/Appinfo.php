<?php

namespace Report;

// collect all information, a la phpinfo on an application
class Appinfo {
    private $info = array();
    private $client = null;
    
    function __construct($client) {
        $this->client = $client;

        // Which extension are being used ? 
        $extensions = array(
                    'Extensions' => array(
                            'ext/bcmath'     => 'Extensions/Extbcmath',
                            'ext/bzip2'      => 'Extensions/Extbzip2',
                            'ext/calendar'   => 'Extensions/Extcalendar',
                            'ext/ctype'      => 'Extensions/Extctype',
                            'ext/ctype'      => 'Extensions/Extcurl',
                            'ext/dba'        => 'Extensions/Extdba',
                            'ext/enchant'    => 'Extensions/Extenchant',
                            'ext/ereg'       => 'Extensions/Extereg',
                            'ext/exif'       => 'Extensions/Extexif',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo',
                            'ext/fdf'        => 'Extensions/Extfdf',
                            'ext/filter'     => 'Extensions/Extfilter',
                            'ext/ftp'        => 'Extensions/Extftp',
                            'ext/gd'         => 'Extensions/Extgd',
                            'ext/gmp'        => 'Extensions/Extgmp',
                            'ext/hash'       => 'Extensions/Exthash',
                            'ext/kdm5'       => 'Extensions/Extkdm5',
                            'ext/ldap'       => 'Extensions/Extldap',
                            'ext/libxml'     => 'Extensions/Extlibxml',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt',
                            'ext/mongo'      => 'Extensions/Extmongo',
                            'ext/mssql'      => 'Extensions/Extmssql',
                            'ext/mysql'      => 'Extensions/Extmysql',
                            'ext/mysqli'     => 'Extensions/Extmysqli',
                            'ext/openssl'    => 'Extensions/Extopenssl',
                            'ext/pcre'       => 'Extensions/Extpcre',
                            'ext/phar'       => 'Extensions/Extphar',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/soap'       => 'Extensions/Extsoap',
                            'ext/sockets'    => 'Extensions/Extsockets',
                            'ext/spl'        => 'Extensions/Extspl',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/standard'   => 'Extensions/Extstandard',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer',
                            'ext/zip'        => 'Extensions/Extzip',
                            'ext/zlib'       => 'Extensions/Extzlib',
//                          'ext/skeleton'   => 'Extensions/Extskeleton',
                    ),
                    'PHP' => array(
                            'Short tags'     => 'Structures/ShortTags',
                            'Incompilable'   => 'Php/Incompilable',
                            
                            'Iffectations'       => 'Structures/Iffectation',
                            'Variable variables' => 'Variables/VariableVariables',

                            '@'  => 'Structures/Noscream',
                            'Eval'       => 'Structures/EvalUsage',
                            'var_dump'   => 'Structures/VardumpUsage',
                            '_once'      => 'Structures/OnceUsage',
                    ),
                    'Namespaces' => array(
                            'Namespaces' => 'Namespaces/Namespacesnames',
                            'Vendor'     => 'Namespaces/Vendor',
                            'Alias'      => 'Namespaces/Alias',
                    ),
                    'Variables' => array(
                            'References' => 'Variables/References',
                            'Array'      => 'Arrays/Arrayindex',
                            'Multidimensional arrays' => 'Arrays/Multidimensional',
                            'PHP arrays' => 'Arrays/Phparrayindex',
                    ),
                    'Functions' => array(
                            'Functions'  => 'Functions/Functionnames',
                            'Redeclared Function'  => 'Functions/RedeclaredPhpFunction',
                            'Recursive Function'  => 'Functions/Recursive',
                            'Closures'   => 'Functions/Closures',
                            'Empty Function'   => 'Functions/EmptyFunction',
                    ),
                    'Classes' => array(
                            'Classes'       => 'Classes/Classnames',
                            'Empty classes' => 'Classes/EmptyClass',
                            'Static variables'   => 'Variables/StaticVariables',
                            'Magic methods'   => 'Classes/MagicMethod',
                    ),
                    'Interfaces' => array(
                            'Interfaces'    => 'Interfaces/Interfacenames',
                            'Empty interfaces' => 'Interfaces/EmptyInterface',
                    ),
                    'Trait' => array(
//                            'Trait'      => 'Trait/Traitnames',
                    ),
                    'Constants' => array(
                            'Constants'     => 'Constants/Constantnames',
                            'PHP constants' => 'Constants/PhpConstantUsage',
                    ),
                    'Goto' => array(
                            'Labels'     => 'Php/Labelnames',
                            'Goto'       => 'Php/Gotonames',
                    ),
                    'Integers' => array(
                            'Integers'    => 'Type/Integer',
                            'Hexadecimal' => 'Type/Hexadecimal',
                            'Octal'       => 'Type/Octal',
                            'Binary'      => 'Type/Binary',
                            'Real'        => 'Type/Real',
                    ),
                    'Strings' => array(
                            'Heredoc'    => 'Type/Heredoc',
                            'Nowdoc'     => 'Type/Nowdoc',
                    )
                    'Exceptions' => array(
                            'Define'        => 'Exceptions/ExceptionDefinition',
                            'Try/Catch'     => 'Exceptions/CaughtExceptions',
                            'Throws'        => 'Exceptions/ThrownExceptions',
                    )
               );

        foreach($extensions as $section => $hash) {
            $this->info['--'.$section] = '';
            foreach($hash as $name => $ext) {
                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].hasNot('notCompatibleWithPhpVersion', null).count()"; 
                $vertices = $this->query($this->client, $queryTemplate);
                $v = $vertices[0][0];
                if ($v == 1) {
                    $this->info[$name] = "Incomp.";
                    continue ;
                } 

                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].count()"; 
                $vertices = $this->query($this->client, $queryTemplate);
                $v = $vertices[0][0];
                if ($v == 0) {
                    $this->info[$name] = "Not run";
                    continue;
                } 

                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].out.any()"; 
                try {
                    $vertices = $this->query($this->client, $queryTemplate);
    
                    $v = $vertices[0][0];
                    $this->info[$name] = $v == "true" ? "Yes" : "No";
                } catch (Exception $e) {
                    // empty catch ? 
                }
            }
        }
    }
    
    function toMarkdown() {
    }
    
    function toArray() {
        return $this->info;
    }

    function query($client, $query) {
        $queryTemplate = $query;
        $params = array('type' => 'IN');
        try {
            $query = new \Everyman\Neo4j\Gremlin\Query($client, $queryTemplate, $params);
            return $query->getResultSet();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
            print "Exception : ".$message."\n";
        
            print $queryTemplate."\n";
            die();
        }
        return $query->getResultSet();
    }

}

?>