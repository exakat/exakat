<?php

namespace Report\Content;

class Appinfo extends \Report\Content {
    private $list = array();
    protected $neo4j = null;

    public function collect() {
        // Which extension are being used ? 
        $extensions = array(
                    'Extensions' => array(
                            'ext/bcmath'     => 'Extensions/Extbcmath',
                            'ext/bzip2'      => 'Extensions/Extbzip2',
                            'ext/calendar'   => 'Extensions/Extcalendar',
                            'ext/crypto'     => 'Extensions/Extcrypto',
                            'ext/ctype'      => 'Extensions/Extctype',
                            'ext/curl'       => 'Extensions/Extcurl',
                            'ext/dba'        => 'Extensions/Extdba',
                            'ext/enchant'    => 'Extensions/Extenchant',
                            'ext/ereg'       => 'Extensions/Extereg',
                            'ext/exif'       => 'Extensions/Extexif',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo',
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
                            'ext/pdo'        => 'Extensions/Extpdo',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/soap'       => 'Extensions/Extsoap',
                            'ext/sockets'    => 'Extensions/Extsockets',
                            'ext/spl'        => 'Extensions/Extspl',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/standard'   => 'Extensions/Extstandard',
                            'ext/tidy'       => 'Extensions/Exttidy',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer',
                            'ext/writer'     => 'Extensions/Extxmlwriter',
                            'ext/yaml'       => 'Extensions/Extyaml',
                            'ext/zip'        => 'Extensions/Extzip',
                            'ext/zlib'       => 'Extensions/Extzlib',
                            'ext/wddx'   => 'Extensions/Extwddx',
                            'ext/xsl'   => 'Extensions/Extxsl',
                            'ext/xmlreader'   => 'Extensions/Extxmlreader',
                            'ext/snmp'   => 'Extensions/Extsnmp',
//                          'ext/skeleton'   => 'Extensions/Extskeleton',
                    ),
                    'PHP' => array(
                            'Short tags'     => 'Structures/ShortTags',
                            'Incompilable'   => 'Php/Incompilable',
                            
//                            'Iffectations'   => 'Structures/Iffectation',
                            'Variable variables' => 'Variables/VariableVariables',

                            '@ operator'     => 'Structures/Noscream',
                            'Eval'           => 'Structures/EvalUsage',
                            'var_dump'       => 'Structures/VardumpUsage',
                            '_once'          => 'Structures/OnceUsage',
                            'halt compiler'  => 'Php/Haltcompiler',
                            'Goto'           => 'Php/Gotonames',
                            'Labels'         => 'Php/Labelnames',
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
                            'Redeclared Function'  => 'Functions/RedeclaredPhpFunctions',
                            'Closures'   => 'Functions/Closures',
                            'Typehint'   => 'Functions/Typephints',
                    ),
                    'Classes' => array(
                            'Classes'    => 'Classes/Classnames',
                            'Interfaces' => 'Interfaces/Interfacenames',
                            'Static variables'   => 'Variables/StaticVariables',
                    ),
                    'Constants' => array(
                            'Constants'     => 'Constants/ConstantDefinition',
                            'PHP constants' => 'Constants/PhpConstantUsage',
                            'PHP constants' => 'Constants/MagicConstantUsage',
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
                    );

        foreach($extensions as $section => $hash) {
            $this->list[$section] = array();
            foreach($hash as $name => $ext) {
                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].hasNot('notCompatibleWithPhpVersion', null).count()"; 
                $vertices = $this->query($this->neo4j, $queryTemplate);
                $v = $vertices[0][0];
                if ($v == 1) {
                    $this->list[$section][$name] = "Incomp.";
                    continue ;
                } 

                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].count()"; 
                $vertices = $this->query($this->neo4j, $queryTemplate);
                $v = $vertices[0][0];
                if ($v == 0) {
                    $this->list[$section][$name] = "Not run";
                    continue;
                } 

                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].out.any()"; 
                try {
                    $vertices = $this->query($this->neo4j, $queryTemplate);
    
                    $v = $vertices[0][0];
                    $this->list[$section][$name] = $v == "true" ? "Yes" : "No";
                } catch (Exception $e) {
                    // empty catch ? 
                }
            }
        }
    }
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function toArray() {
        return $this->list;
    }

    public function query($client, $query) {
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