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
                            'ext/filter'     => 'Extensions/Extfilter',
                            'ext/ftp'        => 'Extensions/Extftp',
                            'ext/gd'         => 'Extensions/Extgd',
                            'ext/hash'       => 'Extensions/Exthash',
                            'ext/kdm5'       => 'Extensions/Extkdm5',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt',
                            'ext/mongo'      => 'Extensions/Extmongo',
                            'ext/mssql'      => 'Extensions/Extmssql',
                            'ext/mysql'      => 'Extensions/Extmysql',
                            'ext/mysqli'     => 'Extensions/Extmysqli',
                            'ext/pcre'       => 'Extensions/Extpcre',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/openssl'    => 'Extensions/Extopenssl',
                            'ext/libxml'    => 'Extensions/Extlibxml',
                            'ext/ldap'       => 'Extensions/Extldap',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',

                            'Short tags'     => 'Structures/ShorTags',
                            'Incompilable'   => 'Php/Incompilable',
                            
                            'Iffectations'       => 'Structures/Iffectation',
                            'Variable variables' => 'Variables/VariableVariables',
                            'Static variables'   => 'Variables/StaticVariables',

                            'Namespaces' => 'Namespaces/Namespacesnames',
                            'Classes'    => 'Classes/Classnames',
                            'Interfaces' => 'Interfaces/Interfacenames',
                            'Functions'  => 'Functions/Functionnames',
                            'Closures'   => 'Functions/Closures',

                            'Constants'  => 'Constants/Constantnames',

                            'Labels'     => 'Php/Labelnames',
                            'Goto'       => 'Php/Gotonames',

                            'Integers'    => 'Type/Integer',
                            'Hexadecimal' => 'Type/Hexadecimal',
                            'Octal'       => 'Type/Octal',
                            'Binary'      => 'Type/Binary',
                            'Real'        => 'Type/Real',

                            'Eval'       => 'Structures/EvalUsage',
                            'var_dump'   => 'Structures/VardumpUsage',
                            '_once'      => 'Structures/OnceUsage',

                            'Heredoc'    => 'Type/Heredoc',
                            'Nowdoc'     => 'Type/Nowdoc',

                            '@'  => 'Structures/Noscream',
                            
                            );
//            $extensions = Analyzer::getAnalyzers('Appinfo');
/*        $extensions = array();

        $files = glob(__DIR__.'/../Analyzer/* /*.php');
        foreach($files as $file) {
            $type = basename(dirname($file));
            if ($type == 'Common') { continue; }
            $analyzer = substr(basename($file), 0, -4);
            
            $class = "\\Analyzer\\".$type."\\".$analyzer;
            $analyzer = new $class(null);
            $themes = $analyzer->getThemes();
            if (in_array('Appinfo', $themes)) {
                $extensions[$analyzer->getAppinfoHeader()] = 'Functions/Functionnames';
            }
        }
*/
//        print_r($extensions);
//        die(__METHOD__);

        foreach($extensions as $name => $ext) {
            $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].count()"; 
            $vertices = $this->query($this->client, $queryTemplate);

            $v = $vertices[0][0];
            if ($v == 0) {
                $this->info[$name] = "NC";
            } else {
                $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('/', '\\\\', $ext)."']].out.any()"; 
                try {
                    $vertices = $this->query($this->client, $queryTemplate);
    
                    $v = $vertices[0][0];
                    $this->info[$name] = $v == "true" ? "Yes" : "No";
                } catch (Exception $e) {
                
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