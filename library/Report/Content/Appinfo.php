<?php

namespace Report\Content;

class Appinfo extends \Report\Content {
    private $list = array();
    protected $neo4j = null;

    public function collect() {
        // Which extension are being used ? 
        $extensions = array(
                    'Extensions' => array(
                            'ext/apc'        => 'Extensions/Extapc.php',
                            'ext/array'      => 'Extensions/Extarray.php',
                            'ext/bcmath'     => 'Extensions/Extbcmath.php',
                            'ext/bzip2'      => 'Extensions/Extbzip2.php',
                            'ext/calendar'   => 'Extensions/Extcalendar.php',
                            'ext/crypto'     => 'Extensions/Extcrypto.php',
                            'ext/ctype'      => 'Extensions/Extctype.php',
                            'ext/curl'       => 'Extensions/Extcurl.php',
                            'ext/cyrus'      => 'Extensions/Extcyrus.php',
                            'ext/date'       => 'Extensions/Extdate.php',
                            'ext/dba'        => 'Extensions/Extdba.php',
                            'ext/dom'        => 'Extensions/Extdom.php',
                            'ext/enchant'    => 'Extensions/Extenchant.php',
                            'ext/ereg'       => 'Extensions/Extereg.php',
                            'ext/exif'       => 'Extensions/Extexif.php',
                            'ext/fdf'        => 'Extensions/Extfdf.php',
                            'ext/ffmpeg'     => 'Extensions/Extffmpeg.php',
                            'ext/file'       => 'Extensions/Extfile.php',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo.php',
                            'ext/filter'     => 'Extensions/Extfilter.php',
                            'ext/ftp'        => 'Extensions/Extftp.php',
                            'ext/gd'         => 'Extensions/Extgd.php',
                            'ext/gmp'        => 'Extensions/Extgmp.php',
                            'ext/gnupg'      => 'Extensions/Extgnupg.php',
                            'ext/hash'       => 'Extensions/Exthash.php',
                            'ext/iconv'      => 'Extensions/Exticonv.php',
                            'ext/info'       => 'Extensions/Extinfo.php',
                            'ext/json'       => 'Extensions/Extjson.php',
                            'ext/kdm5'       => 'Extensions/Extkdm5.php',
                            'ext/ldap'       => 'Extensions/Extldap.php',
                            'ext/libxml'     => 'Extensions/Extlibxml.php',
                            'ext/math'       => 'Extensions/Extmath.php',
                            'ext/mbstring'   => 'Extensions/Extmbstring.php',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt.php',
                            'ext/memcache'   => 'Extensions/Extmemcache.php',
                            'ext/memcached'  => 'Extensions/Extmemcached.php',
                            'ext/ming'       => 'Extensions/Extming.php',
                            'ext/mongo'      => 'Extensions/Extmongo.php',
                            'ext/mssql'      => 'Extensions/Extmssql.php',
                            'ext/mysql'      => 'Extensions/Extmysql.php',
                            'ext/mysqli'     => 'Extensions/Extmysqli.php',
                            'ext/odbc'       => 'Extensions/Extodbc.php',
                            'ext/openssl'    => 'Extensions/Extopenssl.php',
                            'ext/pcntl'      => 'Extensions/Extpcntl.php',
                            'ext/pcre'       => 'Extensions/Extpcre.php',
                            'ext/pdo'        => 'Extensions/Extpdo.php',
                            'ext/pgsql'      => 'Extensions/Extpgsql.php',
                            'ext/phar'       => 'Extensions/Extphar.php',
                            'ext/posix'      => 'Extensions/Extposix.php',
                            'ext/readline'   => 'Extensions/Extreadline.php',
                            'ext/redis'      => 'Extensions/Extredis.php',
                            'ext/reflexion'  => 'Extensions/Extreflexion.php',
                            'ext/sem'        => 'Extensions/Extsem.php',
                            'ext/session'    => 'Extensions/Extsession.php',
                            'ext/shmop'      => 'Extensions/Extshmop.php',
                            'ext/simplexml'  => 'Extensions/Extsimplexml.php',
                            'ext/snmp'       => 'Extensions/Extsnmp.php',
                            'ext/soap'       => 'Extensions/Extsoap.php',
                            'ext/sockets'    => 'Extensions/Extsockets.php',
                            'ext/spl'        => 'Extensions/Extspl.php',
                            'ext/sqlite'     => 'Extensions/Extsqlite.php',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3.php',
                            'ext/sqlsrv'     => 'Extensions/Extsqlsrv.php',
                            'ext/ssh2'       => 'Extensions/Extssh2.php',
                            'ext/standard'   => 'Extensions/Extstandard.php',
                            'ext/tidy'       => 'Extensions/Exttidy.php',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer.php',
                            'ext/wddx'       => 'Extensions/Extwddx.php',
                            'ext/xdebug'     => 'Extensions/Extxdebug.php',
                            'ext/xmlreader'  => 'Extensions/Extxmlreader.php',
                            'ext/xmlrpc'     => 'Extensions/Extxmlrpc.php',
                            'ext/xmlwriter'  => 'Extensions/Extxmlwriter.php',
                            'ext/xsl'        => 'Extensions/Extxsl.php',
                            'ext/yaml'       => 'Extensions/Extyaml.php',
                            'ext/yis'        => 'Extensions/Extyis.php',
                            'ext/zip'        => 'Extensions/Extzip.php',
                            'ext/zlib'       => 'Extensions/Extzlib.php',
                            'ext/zmq'        => 'Extensions/Extzmq.php',
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
                            'Functions'            => 'Functions/Functionnames',
                            'Redeclared PHP Functions' => 'Functions/RedeclaredPhpFunctions',
                            'Closures'             => 'Functions/Closures',

                            'Typehint'             => 'Functions/Typehints',
                            'Static variables'     => 'Variables/StaticVariables',

                            'Dynamic functioncall' => 'Functions/Dynamiccall',

                            'Recursive Functions'  => 'Functions/Recursive',

                    ),

                    'Classes' => array(
                            'Classes'           => 'Classes/Classnames',
                            'Abstract classes'  => 'Classes/Abstractclass',

                            'Interfaces'        => 'Interfaces/Interfacenames',
                            'Traits'            => 'Classes/Traitsnames',

                            'Static properties' => 'Classes/StaticProperties',

                            'Static methods'    => 'Classes/StaticMethods',
                            'Abstract methods'  => 'Classes/Abstractmethods',
                            'Final methods'     => 'Classes/Finalmethods',

                            'Class constants'   => 'Classes/ConstantDefinition',

                            'Magic methods'     => 'Classes/MagicMethod',
                            'Cloning'           => 'Classes/CloningUsage',

                            'PHP 4 constructor' => 'Classes/OldStyleConstructor',
                    ),

                    'Constants' => array(
                            'Constants'           => 'Constants/ConstantUsage',
                            'Variable Constant'   => 'Constants/VariableConstant',
                            'PHP constants'       => 'Constants/PhpConstantUsage',
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