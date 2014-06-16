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
                            'ext/file'       => 'Extensions/Extfile',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo',
                            'ext/filter'     => 'Extensions/Extfilter',
                            'ext/ftp'        => 'Extensions/Extftp',
                            'ext/gd'         => 'Extensions/Extgd',
                            'ext/gmp'        => 'Extensions/Extgmp',
                            'ext/hash'       => 'Extensions/Exthash',
                            'ext/kdm5'       => 'Extensions/Extkdm5',
                            'ext/ldap'       => 'Extensions/Extldap',
                            'ext/libxml'     => 'Extensions/Extlibxml',
                            'ext/mbstring'   => 'Extensions/Extmbstring',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt',
                            'ext/mongo'      => 'Extensions/Extmongo',
                            'ext/mssql'      => 'Extensions/Extmssql',
                            'ext/mysql'      => 'Extensions/Extmysql',
                            'ext/mysqli'     => 'Extensions/Extmysqli',
                            'ext/openssl'    => 'Extensions/Extopenssl',
                            'ext/pcntl'      => 'Extensions/Extpcntl',
                            'ext/pcre'       => 'Extensions/Extpcre',
                            'ext/pdo'        => 'Extensions/Extpdo',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/posix'      => 'Extensions/Extposix',
                            'ext/readline'   => 'Extensions/Extreadline',
                            'ext/shmop'      => 'Extensions/Extshmop',
                            'ext/simplexml'  => 'Extensions/Extsimplexml',
                            'ext/snmp'       => 'Extensions/Extsnmp',
                            'ext/soap'       => 'Extensions/Extsoap',
                            'ext/sockets'    => 'Extensions/Extsockets',
                            'ext/spl'        => 'Extensions/Extspl',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/standard'   => 'Extensions/Extstandard',
                            'ext/tidy'       => 'Extensions/Exttidy',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer',
                            'ext/wddx'       => 'Extensions/Extwddx',
                            'ext/writer'     => 'Extensions/Extxmlwriter',
                            'ext/xmlreader'  => 'Extensions/Extxmlreader',
                            'ext/xmlrpc'     => 'Extensions/Extxmlrpc',
                            'ext/xsl'        => 'Extensions/Extxsl',
                            'ext/yaml'       => 'Extensions/Extyaml',
                            'ext/zip'        => 'Extensions/Extzip',
                            'ext/zlib'       => 'Extensions/Extzlib',
                            'ext/semaphore'  => 'Extensions/Extsem',
                            'ext/apc'        => 'Extensions/Extapc',
//                          'ext/skeleton'   => 'Extensions/Extskeleton',
                    ),
                    'PHP' => array(
                            'Short tags'     => 'Structures/ShortTags',
                            'Echo tags <?='  => 'Structures/EchoTagsUsage',
//                            'Closed scripts'  => 'Php/ClosedTags',
                            'Incompilable'   => 'Php/Incompilable',
                            
//                            'Iffectations'   => 'Structures/Iffectation',

                            '@ operator'     => 'Structures/Noscream',
                            'Alternative syntax' => 'Php/AlternativeSyntax',
                            'Magic constants' => 'Constants/MagicConstantUsage',
                            'halt compiler'  => 'Php/Haltcompiler',
                            'Assertions'     => 'Php/AssertionUsage',

                            'Casting'        => 'Php/CastingUsage',
                            'Nested Loops'   => 'Structures/NestedLoops',

                            'Autoload'       => 'Php/AutoloadUsage',
                            'inclusion'      => 'Structures/IncludeUsage',
                            'include_once'   => 'Structures/OnceUsage',

                            'Throw exceptions' => 'Php/ThrowUsage',
                            'Try/Catch'      => 'Php/TryCatchUsage',
                            'Trigger error'  => 'Php/TriggerErrorUsage',
                            
                            'Goto'           => 'Php/Gotonames',
                            'Labels'         => 'Php/Labelnames',


                            'Eval'           => 'Structures/EvalUsage',
                            'Die/Exit'       => 'Structures/ExitUsage',
                            'var_dump'       => 'Structures/VardumpUsage',

                            'array short syntax'       => 'Structures/ArrayNSUsage',
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
                            'Variable variables' => 'Variables/VariableVariables',
                    ),
                    'Functions' => array(
                            'Functions'  => 'Functions/Functionnames',
                            'Redeclared PHP Functions'  => 'Functions/RedeclaredPhpFunctions',
                            'Recursive Functions'  => 'Functions/Recursive',
                            'Closures'   => 'Functions/Closures',
                            'Typehint'   => 'Functions/Typehints',
                            'Static variables'   => 'Variables/StaticVariables',
                    ),
                    'Classes' => array(
                            'Classes'    => 'Classes/Classnames',
                            'Abstract classes' => 'Classes/Abstractclass',

                            'Interfaces' => 'Interfaces/Interfacenames',
                            'Traits' => 'Classes/Traitsnames',

                            'Static properties'   => 'Classes/StaticProperties',

                            'Static methods'   => 'Classes/StaticMethods',
                            'Abstract methods' => 'Classes/Abstractmethods',
                            'Final methods' => 'Classes/Finalmethods',

                            'Class constants' => 'Classes/ConstantDefinition',

                            'Magic methods' => 'Classes/MagicMethod',
                            'Cloning' => 'Classes/CloningUsage',

                            'PHP 4 constructor' => 'Classes/OldStyleConstructor',
                    ),
                    'Constants' => array(
                            'Constants'     => 'Constants/ConstantUsage',
                            'Variable Constant' => 'Constants/VariableConstant',
                            'PHP constants' => 'Constants/PhpConstantUsage',
                            'PHP Magic constants' => 'Constants/MagicConstantUsage',
                    ),
                    'Numbers' => array(
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
            die(__METHOD__);
        }
        return $query->getResultSet();
    }
}

?>