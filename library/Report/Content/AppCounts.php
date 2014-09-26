<?php

namespace Report\Content;

class AppCounts extends \Report\Content {
    private $list = array();
    protected $neo4j = null;

    public function collect() {
        // Which extension are being used ? 
        $extensions = array(
                    'Summary' => array(
                            'Namespaces'     => 'Namespace',
                            'Classes'        => 'Class',
                            'Interfaces'     => 'Interface',
                            'Trait'          => 'Trait',
                            'Function'       => array('index' => 'Function', 'Below' => 'in("ELEMENT").in("BLOCK").hasNot("atom", "Class")'),
                            'Variables'      => array('index' => 'Variable', 'Unique' => 'code'),
                            'Constants'      => array('index' => 'Constant'),
                     ),
                    'Classes' => array(
                            'Classes'        => 'Class',
                            'Constants'      => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Const")'),
                            'Properties'     => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{!it.out("STATIC").any()}.out("DEFINE")'),
                            'Static properties' => array('index' => 'Class', 
                                                         'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{it.out("STATIC").any()}.out("DEFINE")'),
                            'Methods'        => array('index' => 'Class', 
                                                      'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Function").filter{!it.out("STATIC").any()}'),
                            'Static methods' => array('index' => 'Class', 
                                                                'Below' => 'out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("STATIC").any()}'),
                     ),
                    'Structures' => array(
                            'Ifthen'        => 'Ifthen',
                            'Switch'        => 'Switch',
                            'For'           => 'For',
                            'Foreach'       => 'Foreach',
                            'While'         => 'While',
                            'Do..while'     => 'Dowhile',
                            'New'           => 'New',
                            'Clone'         => 'Clone',
                            'Throw'         => 'Throw',
                            'Try'           => 'Try',
                            'Catch'         => 'Catch',
                            'Finally'       => 'Finally',
                            'Yield'         => 'Yield',
                            '?  :'          => 'Ternary',
                            'Variables constants' => 'Constants\\VariableConstants',
                            'Variables variables' => 'Variables\\VariableVariable',
                            'Variables functions' => 'Functions\\Dynamiccall',
                            'Variables classes' => 'Classes\\VariableClasses',
                            
                     ),
                    );

        foreach($extensions as $section => $hash) {
            $this->list[$section] = array();
            foreach($hash as $name => $ext) {
                if (is_string($ext)) {
                    if (strpos($ext, '\\') === false) {
                        $queryTemplate = "g.idx('$ext')[['token':'node']].count()"; 
                    } else {
                        $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $ext)."']].out('ANALYZED').count()"; 
                    }
                } elseif (isset($ext['Unique'])) {
                    $queryTemplate = "g.idx('{$ext['index']}')[['token':'node']].{$ext['Unique']}.unique().count()"; 
                } elseif (isset($ext['Below'])) {
                    $queryTemplate = "g.idx('{$ext['index']}')[['token':'node']].{$ext['Below']}.count()"; 
                } else {
                    $queryTemplate = "g.idx('{$ext['index']}')[['token':'node']].count()"; 
                }
                $vertices = $this->query($this->neo4j, $queryTemplate);
                $v = $vertices[0][0];
                $this->list[$section][$name] = $v;
                continue;
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
    
    public function getColumnTitles() {
        return array('Object', 'Count');
    }
}

?>