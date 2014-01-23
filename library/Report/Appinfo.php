<?php

namespace Report;

// collect all information, a la phpinfo on an application
class Appinfo {
    private $info = array();
    private $client = null;
    
    function __construct($client) {
        $this->client = $client;

        // Which extension are being used ? 
        $extensions = array('ext/mcrypt' => 'Extensions/Extmcrypt',
                            'ext/mysqli'  => 'Extensions/Extmysqli',
                            'ext/pcre'  => 'Extensions/Extpcre',
                            'ext/kdm5'   => 'Extensions/Extkdm5',
                            'ext/mssql'   => 'Extensions/Extmssql',
                            'ext/bcmath'   => 'Extensions/Extbcmath',
                            'ext/bzip2'   => 'Extensions/Extbzip2',
                            
                            'Iffectations' => 'Structures/Iffectation',
                            'Variable variables' => 'Variables/VariableVariables',
                            'Static variables' => 'Variables/StaticVariables',

                            'Namespaces' => 'Namespaces/Namespacesnames',
                            'Classes'    => 'Classes/Classnames',
                            'Interfaces' => 'Interfaces/Interfacenames',
                            'Functions'  => 'Functions/Functionnames',
                            'Functions'  => 'Functions/Closure',

                            'Heredoc'    => 'Type/Heredoc',
                            'Nowdoc'     => 'Type/Nowdoc',

                            '@'  => 'Structures/Noscream',
                            
                            );
//            $extensions = Analyzer::getAnalyzers('Appinfo');

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