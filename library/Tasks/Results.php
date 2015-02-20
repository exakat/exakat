<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class Results implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $client = new Client();

/*
        $args = $argv;
        if ($id = array_search('-json', $args)) {
            define('FORMAT', 'JSON');
            unset($args[$id]);
            $args = array_values($args);
        } elseif ($id = array_search('-text', $args)) {
            define('FORMAT', 'TEXT');
            unset($args[$id]);
            $args = array_values($args);
        } elseif ($id = array_search('-odt', $args)) {
            define('FORMAT', 'ODT');
            unset($args[$id]);
            $args = array_values($args);
        } elseif ($id = array_search('-markdown', $args)) {
            define('FORMAT', 'MARKDOWN');
            unset($args[$id]);
            $args = array_values($args);
        } elseif ($id = array_search('-csv', $args)) {
            define('FORMAT', 'CSV');
            unset($args[$id]);
            $args = array_values($args);
        } elseif ($id = array_search('-html', $args)) {
            define('FORMAT', 'HTML');
            unset($args[$id]);
            $args = array_values($args);
        } else {
            define('FORMAT', 'TEXT');
        }

        if ($id = array_search('-o', $args)) {
            define('OUTPUT', true);
        } else {
            define('OUTPUT', false);
        }

        if ($id = array_search('-f', $args)) {
            define('FILE', $args[$id + 1]);
        } else {
            define('FILE', false);
        }

        if ($id = array_search('-D', $args)) {
            define('STYLE', 'DISTINCT');
        } elseif ($id = array_search('-C', $args)) {
            define('STYLE', 'COUNTED_ALL');
        } elseif ($id = array_search('-B', $args)) {
            define('STYLE', 'BOOLEAN');
        } elseif ($id = array_search('-G', $args)) {
            define('STYLE', 'COUNTED');
        } else {
            define('STYLE', 'ALL');
        }
*/
        $analyzer = $config->program;
        $analyzerClass = \Analyzer\Analyzer::getClass($analyzer);

        if ("Analyzer\\".str_replace('/', '\\', $analyzer) != $analyzerClass) {
            print "'$analyzer' doesn't exists. Aborting\n";
    
            $r = \Analyzer::getSuggestionClass($analyzer);
            if (count($r) > 0) {
                print "did you mean : ".implode(', ', str_replace('_', '/', $r))."\n";
            }
    
            exit;
        }

        $analyzer = str_replace('\\', '\\\\', $analyzerClass);

        $return = array();
        if ($config->style == 'BOOLEAN') {
            $queryTemplate = "g.idx('analyzers')[['analyzer':'$analyzer']].out.any()"; 
            $vertices = query($client, $queryTemplate);

            $return[] = $vertices[0][0];
        } elseif ($config->style == 'COUNTED_ALL') {
            $queryTemplate = "g.idx('analyzers')[['analyzer':'$analyzer']].out.count()"; 
            $vertices = query($client, $queryTemplate);

            $return[] = $vertices[0][0];
        } elseif ($config->style == 'ALL') {
              $query = <<<GREMLIN
        g.idx('analyzers')[['analyzer':'$analyzer']].out.sideEffect{m = ['Fullcode':it.fullcode, 'File':'None', 'Line':it.line, 'Namespace':'Globaln', 'Class':'Globalc', 'Function':'Globalf' ]; }.as('x').
        transform{ it.in.loop(1){true}{ it.object.token in ['T_CLASS', 'T_FUNCTION', 'T_NAMESPACE', 'T_FILENAME']}.each{ m[it.atom] = it.code;} m; }.transform{ m; }
GREMLIN;

            $vertices = $this->query($client, $query);

            $return = array();
            foreach($vertices as $k => $v) {
                $row = array();
                $row[] = $v[0]['Fullcode'];
                $row[] = $v[0]['File'];
                $row[] = $v[0]['Line'];
                $row[] = $v[0]['Namespace'];
                $row[] = $v[0]['Class'];
                $row[] = $v[0]['Function'];
                $return[] = $row;
            }
        } elseif ($config->style == 'DISTINCT') {
            $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\$analyzer']].out.code.unique()"; 
            $vertices = query($client, $queryTemplate);

            $return = array();
            foreach($vertices as $k => $v) {
                $return[] = $v[0];
            }
        } elseif ($config->style == 'COUNTED') {
            $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'Analyzer\\\\$analyzer']].out.groupCount(m){it.code}.cap"; 
            $vertices = $this->query($client, $queryTemplate);

            $return = array();
            foreach($vertices[0][0] as $k => $v) {
                $return[$k] = $v;
            }
        }

        if ($config->format == 'Text') {
            $text = '';
            foreach($return as $k => $v) {
                if ($config->style == 'COUNTED') {
                    $text .= "$k => $v\n";
                } else {
                    $text .= implode(', ', $v)."\n";
                }
            }
    
        } elseif ($config->format == 'JSON') {
            $text = json_encode($return);
        } elseif ($config->format == 'CSV') {
            $text = array(array('Code', 'File', 'Namespace', 'Class', 'Function'));
            foreach($return as $k => $v) {
                if (is_array($v)) {
                    $text[] = $v;
                } else {
                    $text[] = array($k, $v);
                }
            }
        } elseif ($config->format == 'MARKDOWN' || $config->format == 'HTML' || $config->format == 'ODT') {
            $text = '';
            foreach($return as $k => $r) {
                if ($config->style == 'COUNTED') {
                    $text .= "+ $k => $r\n";
                } else {
                    $text .= "+ $k\n";
                    if (is_array($r)) {
                        $text .= "  + ".implode("\n  + ", $r)."\n";
                    } else {
                        $text .= "+ $r\n";
                    }
                }
            }
        } else {
        // default behavior
            print_r($return);
        }

        if ($config->format == 'HTML'|| $config->format == 'ODT') {
            $text = Markdown::defaultTransform($text);
        }

        if ($config->output) {
            print $text;
        }

        $extensions = array('JSON' => 'json',
                            'HTML' => 'html',
                            'MARKDOWN' => 'md',
                            'ODT' => 'odt',
                            'Text' => 'txt',
                            'CSV' => 'csv');

        if ($config->file) {
            $name = $config->file.'.'.$extensions[$config->format];
            if (file_exists($name)) {
                print "$name already exists. Aborting\n";
                die();
            }

            if ($config->format == 'ODT') {
                $name1 = FILE.'.'.$extensions['HTML'];
                file_put_contents($name1, $text);

                $name = FILE.'.'.$extensions[$config->format];
                shell_exec('pandoc -o '.$name.' '.$name1);
                unlink($name1);
            } elseif ($config->format == 'CSV') {
                $fp = fopen($name, 'w');
                foreach($text as $t) {
                    fputcsv($fp, $t);
                }
                fclose($fp);
            } else {
                file_put_contents($name, $text);
            }
        }
    }

    private function query($client, $query) {
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