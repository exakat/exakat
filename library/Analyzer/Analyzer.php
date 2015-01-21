<?php

namespace Analyzer;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

class Analyzer {
    protected $client = null;
    protected $code = null;

    protected $name = null;
    protected $description = null;
    protected $appinfo = null;
    
    static public $datastore = null;
    
    protected $rowCount = 0;

    private $queries = array();
    private $queriesArguments = array();
    private $methods = array();
    private $arguments = array();
    
    protected $config = null;
    
    static public $analyzers = array();
    
    protected $apply = null;

    protected $phpVersion = 'Any';
    protected $phpConfiguration = 'Any';

    protected $severity = self::S_NONE; // Default to None. 
    const S_CRITICAL = 'Critical';
    const S_MAJOR = 'Major';
    const S_MINOR = 'Minor';
    const S_NOTE = 'Note';
    const S_NONE = 'None';

    protected $timeToFix = self::T_NONE; // Default to no time (Should not display)
    const T_NONE = '0';
    const T_INSTANT = '5';
    const T_QUICK = '30';
    const T_SLOW = '60';
    const T_LONG = '360';
    
    protected $themes = array();
    static public $docs = null;

    public function __construct($client) {
        $this->client = $client;
        $this->analyzer = get_class($this);
        $this->analyzerQuoted = str_replace('\\', '\\\\', $this->analyzer);
        $this->analyzerIsNot($this->analyzer);

        $this->code = $this->analyzer;
        
        if (Analyzer::$docs === null) {
            Analyzer::$docs = new Docs(dirname(dirname(dirname(__FILE__))).'/data/analyzers.sqlite');
        }
        
        $this->apply = new AnalyzerApply();
        $this->apply->setAnalyzer($this->analyzer);
    } 
    
    public function setConfig($config) {
        $this->config = $config;
    }
    
    public static function getClass($name) {
        // accepted names : 
        // PHP full name : Analyzer\\Type\\Class
        // PHP short name : Type\\Class
        // Human short name : Type/Class
        // Human shortcut : Class (must be unique among the classes)
        
        if (strpos($name, '\\') !== false) {
            if (substr($name, 0, 9) == 'Analyzer\\') {
                $class = $name;
            } else {
                $class = 'Analyzer\\'.$name;
            }
        } elseif (strpos($name, '/') !== false) {
            $class = 'Analyzer\\'.str_replace('/', '\\', $name);
        } elseif (strpos($name, '/') === false) {
            $files = glob('library/Analyzer/*/'.$name.'.php');
            if (count($files) == 0) {
                return false; // no class found
            } elseif (count($files) == 1) {
                $class = str_replace('/', '\\', substr($files[0], 8, -4));
            } else {
                // too many options here...
                return false;
            }
        } else {
            $class = $name;
        }
        
        if (class_exists($class)) {
            $actualClassName = new \ReflectionClass($class);
            if ($class != $actualClassName->getName()) {
                // problems with the case 
                return false;
            } else {
                return $class;
            }
        } else {
            return false;
        }
    }
    
    public static function getSuggestionClass($name) {
        $list = glob('library/Analyzer/*/*.php');
        $r = array();
        foreach($list as $id => $c) {
            $c = substr($c, 17, -4);
            $c = str_replace('/', '_', $c);
            $l = levenshtein($c, $name);
            if ($l < 8) {
                $r[] = $c;
            }
        }
        
        return $r;
    }
    
    public static function getInstance($name, $client) {
        if ($analyzer = Analyzer::getClass($name)) {
            return new $analyzer($client);
        } else {
            print "No such class as '$name'\n";
            return null;
        }
    }
    
    public function getDescription($lang = 'en') {
        if ($this->description === null) {
            $filename = './human/'.$lang.'/'.str_replace("\\", "/", str_replace("Analyzer\\", "", $this->analyzer)).'.ini';
            
            if (!file_exists($filename)) {
                $human = array();
            } else {
                $human = parse_ini_file($filename);
            }

            if (isset($human['description'])) {
                $this->description = $human['description'];
            } else {
                $this->description = '';
            }

            if (isset($human['name'])) {
                $this->name = $human['name'];
            } else {
                $this->name = $this->analyzer;
            }

            if (isset($human['appinfo'])) {
                $this->appinfo = $human['appinfo'];
            } else {
                $this->appinfo = $this->analyzer;
            }
        }
        
        return $this->description;
    }

    public function getName($lang = 'en') {
        if ($this->name === null) {
            $this->getDescription($lang);
        }

        return $this->name;
    }

    static public function getThemeAnalyzers($theme) {
        if (Analyzer::$docs === null) {
            Analyzer::$docs = new Docs('./data/analyzers.sqlite');
        }
        return Analyzer::$docs->getThemeAnalyzers($theme);
    }
    
    public function getThemes() {
        if (empty($this->themes)) {
            $r =  array();
        } else {
            $r =  $this->themes;
        }
        
        return $r;
    }

    public function getAppinfoHeader($lang = 'en') {
        if ($this->appinfo === null) {
            $this->getDescription($lang);
        }

        return $this->appinfo;
    }
    
    static public function getAnalyzers($theme) {
        return Analyzer::$analyzers[$theme];
    }

    private function addMethod($method, $arguments = null) {
        if ($arguments === null) {
            $this->methods[] = $method;
        } else {
            if (func_num_args() > 2) {
                $arguments = func_get_args();
                array_shift($arguments);
                $argnames = array(str_replace('***', '%s', $method));
                foreach($arguments as $arg) {
                    $argname = 'arg'.(count($this->arguments));
                    $this->arguments[$argname] = $arg;
                    $argnames[] = $argname;
                }
                $this->methods[] = call_user_func_array('sprintf', $argnames);
            } else {
                $argname = 'arg'.count($this->arguments);
                $this->arguments[$argname] = $arguments;
                $this->methods[] = str_replace('***', $argname, $method);
            }
        }

        return $this;
    }
    
    public function init() {
        $result = $this->query("g.getRawGraph().index().existsForNodes('analyzers');");
        if ($result[0][0] == 0) {
            $this->query("g.createIndex('analyzers', Vertex)");
        }
        
        $query = "g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']]";
        $res = $this->query($query);
        
        if (isset($res[0]) && count($res[0]) == 1) {
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']].outE('ANALYZED').each{
    g.removeEdge(it);
}

GREMLIN;
            $this->query($query);
        } else {
            $this->code = addslashes($this->code);
            $query = <<<GREMLIN
x = g.addVertex(null, [analyzer:'{$this->analyzerQuoted}', analyzer:'true', line:0, description:'Analyzer index for {$this->analyzerQuoted}', code:'{$this->code}', fullcode:'{$this->code}',  atom:'Index', token:'T_INDEX']);

g.idx('analyzers').put('analyzer', '{$this->analyzerQuoted}', x);

GREMLIN;
            $this->query($query);
        }
    }

    public function isDone() {
        $result = $this->query("g.getRawGraph().index().existsForNodes('analyzers');");
        if ($result[0][0] == 0) {
            $this->query("g.createIndex('analyzers', Vertex)");

            return false;
        }
        
        $query = "g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']].count() == 1";
        $res = $this->query($query);
        return (bool) $res[0][0];
    }

    public function checkphpConfiguration($Php) {
        // this handles Any version of PHP
        if ($this->phpConfiguration == 'Any') {
            return true;
        }
        
        foreach($this->phpConfiguration as $ini => $value) {
            if ($Php->getConfiguration($ini) != $value) { return false; }
        }
        
        return true;
    }
    
    public function checkPhpVersion($version) {
        // this handles Any version of PHP
        if ($this->phpVersion == 'Any') {
            return true;
        }

        // version and above 
        if ((substr($this->phpVersion, -1) == '+') && version_compare($version, $this->phpVersion) >= 0) {
            return true;
        } 

        // up to version  
        if ((substr($this->phpVersion, -1) == '-') && version_compare($version, $this->phpVersion) <= 0) {
            return true;
        } 

        // version range 1.2.3-4.5.6
        if (strpos($this->phpVersion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpVersion);
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
        } 
        
        // One version only
        if (version_compare($version, $this->phpVersion) == 0) {
            return true;
        } 
        
        // Default behavior if we don't understand : 
        return false;
    }

    // @doc return the list of dependences that must be prepared before the execution of an analyzer
    // @doc by default, nothing. 
    public function dependsOn() {
        return array();
    }
    
    public function setApplyBelow($applyBelow = true) {
        $this->apply->setApplyBelow($applyBelow);
        
        $this->addMethod('sideEffect{ applyBelowRoot = it }');
        
        return $this;
    }

    public function query($queryString, $arguments = null) {
        if ($arguments === null) {
            $arguments = array('type' => 'IN');
        }

        try {
            $result = new \Everyman\Neo4j\Gremlin\Query($this->client, $queryString, $arguments);
            return $result->getResultSet();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
            print 'Exception : '.$message."\n";
            
            print $queryString."\n";
            print_r($this->arguments);
            die(__METHOD__);
        }
    }

    public function _as($name) {
        $this->methods[] = 'as("'.$name.'")';
        
        return $this;
    }

    public function back($name) {
        $this->methods[] = 'back(\''.$name.'\')';
        
        return $this;
    }
    
    public function ignore() {
        // used to execute some code but not collect any node
        $this->methods[] = 'filter{ 1 == 0; }';
    }

    public function tokenIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{it.token in *** }', $atom);
        } else {
            $this->addMethod('has("token", ***)', $atom);
        }
        
        return $this;
    }

    public function tokenIsNot($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{!(it.token in ***)}', $atom);
        } else {
            $this->addMethod('hasNot("token", ***)', $atom);
        }
        
        return $this;
    }
    
    public function atomIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{it.atom in ***}', $atom);
        } else {
            $this->addMethod('has("atom", ***)', $atom);
        }
        
        return $this;
    }

    public function atomIsNot($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{!(it.atom in ***) }', $atom);
        } else {
            $this->addMethod('hasNot("atom", ***)', $atom);
        }
        
        return $this;
    }

    public function classIs($class) {
        if (is_array($class)) {
            $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS" || it.out("NAME").next().code in ***}.back("classIs")', $class);
        } else {
            if ($class == 'Global') {
                $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS"}.back("classIs")');
            } else {
                $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS" || it.out("NAME").next().code != ***}.back("classIs")', $class);
            }
        }
        
        return $this;
    }

    public function classIsNot($class) {
        if (is_array($class)) {
            $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("classIsNot")', $class);
        } else {
            if ($class == 'Global') {
                $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS"])}.back("classIsNot")');
            } else {
                $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("classIsNot")', $class);
            }
        }
        
        return $this;
    }

    public function traitIs($trait) {
        if (is_array($trait)) {
            $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT" || !(it.out("NAME").next().code in ***)}.back("traitIs")', $trait);
        } else {
            if ($trait == 'Global') {
                $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT"}.back("traitIs")');
            } else {
                $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT" || it.out("NAME").next().code != ***}.back("traitIs")', $trait);
            }
        }
        
        return $this;
    }

    public function traitIsNot($trait) {
        if (is_array($trait)) {
            $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("traitIsNot")', $trait);
        } else {
            if ($trait == 'Global') {
                $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT"])}.back("traitIsNot")');
            } else {
                $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("traitIsNot")', $trait);
            }
        }
        
        return $this;
    }
    
    public function functionIs($function) {
        if (is_array($function)) {
            $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FILENAME" || it.out("NAME").next().code in ***}.back("functionIs")', $function);
        } else {
            if ($function == 'Global') {
                $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FUNCTION"}.back("functionIs")');
            } else {
                $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FILENAME" || it.out("NAME").next().code != ***}.back("functionIs")', $function);
            }
        }
        
        return $this;
    }

    public function functionIsNot($function) {
        if (is_array($function)) {
                $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("functionIsNot")', $function);
        } else {
            if ($function == 'Global') {
                $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION"])}.back("functionIsNot")');
            } else {
                $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("functionIsNot")', $function);
            }
        }
        
        return $this;
    }

    public function namespaceIs($namespace) {
        if (is_array($namespace)) {
            $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_NAMESPACE" && it.code in *** }.back("namespaceIs")', $namespace);
        } else {
            if ($namespace == 'Global') {
                $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_FILENAME" || it.out("NAMESPACE").next().code == "Global" }.back("namespaceIs")');
            } else {
                $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_NAMESPACE" && it.code == *** }.back("namespaceIs")', $namespace);
            }
        }
        
        return $this;
    }

    public function atomInside($atom) {
        if (is_array($atom)) {
            $this->addMethod('out().loop(1){true}{it.object.atom in ***}', $atom);
        } else {
            $this->addMethod('out().loop(1){true}{it.object.atom == ***}', $atom);
        }
        
        return $this;
    }

    public function noAtomInside($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{ it.as("loop").out().loop("loop"){true}{it.object.atom in ***}.any() == false}', $atom);
        } else {
            $this->addMethod('filter{ it.as("loop").out().loop("loop"){true}{it.object.atom == ***}.any() == false}', $atom);
        }
        
        return $this;
    }

    public function atomAboveIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('in().loop(1){true}{it.object.atom in ***}', $atom);
        } else {
            $this->addMethod('in().loop(1){true}{it.object.atom == ***}', $atom);
        }
        
        return $this;
    }
    
    public function trim($property, $chars = '\'\"') {
        $this->addMethod('transform{it.'.$property.'.replaceFirst("^['.$chars.']?(.*?)['.$chars.']?\$", "\$1")}');
        
        return $this;
    }

    public function analyzerIs($analyzer) {
        if (is_array($analyzer)) {
            $this->addMethod('filter{ it.in("ANALYZED").filter{ it.code in ***}.any()}', $analyzer);
        } else {
            if ($analyzer == 'self') {
                $analyzer = $this->analyzer;
            }
            $this->addMethod('filter{ it.in("ANALYZED").has("code", ***).any()}', $analyzer);
        }
        
        return $this;
    }

    public function analyzerIsNot($analyzer) {

        if (is_array($analyzer)) {
            $this->addMethod('filter{ it.in("ANALYZED").filter{ it.code in ***}.any() == false}', $analyzer);
        } else {
            if ($analyzer == 'self') {
                $analyzer = $this->analyzer;
            }
            $this->addMethod('filter{ it.in("ANALYZED").has("code", ***).any() == false}', $analyzer);
        }

        return $this;
    }

    public function is($property, $value= "'true'") {
        if ($value === null) {
            $this->addMethod("has('$property', null)");
        } else {
            $this->addMethod("filter{ it.$property == ***;}", $value);
        }

        return $this;
    }

    public function isMore($property, $value = '0') {
        if (is_int($value)) {
            $this->addMethod('filter{ it.$property > ***;}', $value);
        } else {
            // this is a variable name
            $this->addMethod('filter{ it.$property > $value;}', $value);
        }

        return $this;
    }

    public function isLess($property, $value= '0') {
        if (is_int($value)) {
            $this->addMethod('filter{ it.$property < ***;}', $value);
        } else {
            // this is a variable name
            $this->addMethod('filter{ it.$property < $value;}', $value);
        }

        return $this;
    }

    public function isNot($property, $value= "'true'") {
        if ($value === null) {
            $this->addMethod("hasNot('$property', null)");
        } else {
            $this->addMethod("filter{ it.$property != ***;}", $value);
        }
        
        return $this;
    }

    public function hasRank($value = '0', $link = 'ARGUMENT') {
        if ($value == 'first') {
            $this->addMethod("has('rank','0')");
        } elseif ($value === 'last') {
            $this->addMethod("filter{it.rank == it.in('$link').out('$link').count() - 1}");
        } elseif ($value === '2last') {
            $this->addMethod("filter{it.rank == it.in('$link').out('$link').count() - 2}");
        } else {
            $this->addMethod("filter{it.rank == ***}", abs(intval($value)));
        }

        return $this;
    }

    public function noChildWithRank($edgeName, $rank = '0') {
        if ($rank === 'first') {
            $this->addMethod("filter{ it.out(***).has('rank','0').any() == false }", $edgeName);
        } elseif ($rank === 'last') {
            $this->addMethod("filter{ it.out(***).has('rank',it.in(***).count() - 1).any() == false }", $edgeName, $edgeName);
        } elseif ($rank === '2last') {
            $this->addMethod("filter{ it.out(***).has('rank',it.in(***).count() - 2).any() == false }", $edgeName, $edgeName);
        } else {
            $this->addMethod("filter{ it.out(***).has('rank', ***).any() == false}", $edgeName, abs(intval($rank)));
        }

        return $this;
    }

    public function code($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{it.code'.$caseSensitive.' in ***}', $code);
        } else {
            $this->addMethod('filter{it.code'.$caseSensitive.' == ***}', $code);
        }
        
        return $this;
    }

    public function codeIsNot($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{!(it.code'.$caseSensitive.' in ***)}', $code);
        } else {
            $this->addMethod('filter{it.code'.$caseSensitive.' != ***}', $code);
        }
        
        return $this;
    }

    public function noDelimiter($code, $caseSensitive = false) {
        $this->addMethod('has("atom", "String")', $code);

        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{it.noDelimiter'.$caseSensitive.' in ***}', $code);
        } else {
            $this->addMethod('filter{it.noDelimiter'.$caseSensitive.' == ***}', $code);
        }
        
        return $this;
    }

    public function noDelimiterIsNot($code, $caseSensitive = false) {
        $this->addMethod('has("atom", "String")', $code);

        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{!(it.noDelimiter'.$caseSensitive.' in ***)}', $code);
        } else {
            $this->addMethod('filter{it.noDelimiter'.$caseSensitive.' != ***}', $code);
        }
        
        return $this;
    }

    public function fullnspath($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{it.fullnspath'.$caseSensitive.' in ***}', $code);
        } else {
            $this->addMethod('filter{it.fullnspath'.$caseSensitive.' == ***}', $code);
        }
        
        return $this;
    }

    public function fullnspathIsNot($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{!(it.fullnspath'.$caseSensitive.' in ***)}', $code);
        } else {
            $this->addMethod('filter{it.fullnspath'.$caseSensitive.' != ***}', $code);
        }
        
        return $this;
    }
    
    public function codeIsPositiveInteger() {
        $this->addMethod('filter{ if( it.code.isInteger()) { it.code > 0; } else { true; }}', null); // may be use toInteger() ? 

        return $this;
    }

    public function samePropertyAs($property, $name, $caseSensitive = false) {
        if ($caseSensitive || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        $this->addMethod('filter{ it.'.$property.$caseSensitive.' == '.$name.$caseSensitive.'}');

        return $this;
    }

    public function notSamePropertyAs($property, $name, $caseSensitive = false) {
        if ($caseSensitive || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        $this->addMethod('filter{ it.'.$property.$caseSensitive.' != '.$name.$caseSensitive.'}');

        return $this;
    }

    public function savePropertyAs($property, $name) {
        $this->addMethod("sideEffect{ $name = it.$property; }");

        return $this;
    }

    public function fullcodeTrimmed($code, $trim = "\"'", $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        $trim = addslashes($trim);
        if (is_array($code)) {
            $this->methods[] = "filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") in ['".implode("', '", $code)."']}";
        } else {
            $this->methods[] = "filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") == '$code'}";
        }
        
        return $this;
    }
    
    public function fullcode($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{it.fullcode'.$caseSensitive.' in ***}', $code);
        } else {
            $this->addMethod('filter{it.fullcode'.$caseSensitive.' == ***}', $code);
        }
        
        return $this;
    }
    
    public function fullcodeIsNot($code, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            if (is_array($code)) {
                foreach($code as $k => $v) { 
                    $code[$k] = strtolower($v); 
                }
            } else {
                $code = strtolower($code);
            }
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->methods[] = "filter{!(it.fullcode$caseSensitive in ['".implode("', '", $code)."'])}";
        } else {
            $this->methods[] = "filter{it.fullcode$caseSensitive != '$code'}";
        }
        
        return $this;
    }

    public function isUppercase($property = 'fullcode') {
        $this->methods[] = 'filter{it.$property == it.$property.toUpperCase()}';
    }

    public function isNotLowercase($property = 'fullcode') {
        $this->methods[] = 'filter{it.$property != it.$property.toLowerCase()}';
    }

    public function filter($filter) {
        $this->methods[] = 'filter{ $filter }';
    }

    public function codeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ? 
        $this->methods[] = "filter{it.code.length() $length}";
    }

    public function fullcodeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ? 
        $this->methods[] = "filter{it.fullcode.length() $length}";

        return $this;
    }

    public function groupCount($column) {
        $this->methods[] = "groupCount(m){it.$column}";
        
        return $this;
    }

    public function eachCounted($variable, $times) {
        $this->methods[] = <<<GREMLIN
groupBy(m){{$variable}}{it}.iterate(); 
// This is plugged into each{}
m.findAll{ it.value.size() == $times}.values().flatten().each{ n.add(it); }
GREMLIN;

        return $this;
    }
    
    public function eachNotCounted($variable, $times = 1) {
        $this->methods[] = <<<GREMLIN
groupBy(m){{$variable}}it}.iterate(); 
// This is plugged into each{}
m.findAll{ it.value.size() != $times}.values().flatten().each{ n.add(it); }
GREMLIN;

        return $this;
    }

    public function eachCountedMoreThan($variable, $times = 1) {
        $this->methods[] = <<<GREMLIN
groupBy(m){{$variable}}{it}.iterate(); 
// This is plugged into each{}
m.findAll{ it.value.size() >= $times}.values().flatten().each{ n.add(it); }
GREMLIN;

        return $this;
    }
    
    public function countIs($comparison) {
        $this->addMethod('aggregate().filter{ it.size '.$comparison.'}', null);
        
        return $this;
    }

    public function regex($column, $regex) {
        $this->methods[] = <<<GREMLIN
filter{ (it.$column =~ "$regex" ).getCount() > 0 }
GREMLIN;

        return $this;
    }

    public function regexNot($column, $regex) {
        $this->methods[] = <<<GREMLIN
filter{ (it.$column =~ "$regex" ).getCount() == 0 }
GREMLIN;

        return $this;
    }

    protected function outIs($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod("out('".implode("', '", $edgeName)."')");
        } else {
            $this->addMethod('out(***)', $edgeName);
        }
        
        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function outIsIE($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod("transform{ a = it; while (a.out('".implode("', '", $edgeName)."').any()) { a = a.out('".implode("', '", $edgeName)."').next(); }; a;}");
        } else {
            $this->addMethod("transform{ a = it; while (a.out('$edgeName').any()) { a = a.out('$edgeName').next(); };  a;}", $edgeName);
        }
        
        return $this;
    }

    public function outIsnt($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod("filter{ it.out('".implode("', '", $edgeName)."').count() == 0}");
        } else {
            $this->addMethod('filter{ it.out(***).count() == 0}', $edgeName);
        }
        
        return $this;
    }

    public function rankIs($edgeName, $rank) {
        if (is_array($edgeName)) {
            // @todo
            die(" I don't understand arrays in rankIs()");
        }

        if ($rank == 'first') {
            $rank = 0;
            $this->addMethod("out(***).filter{it.getProperty('rank')  == ***}", $edgeName, $rank);
        } else if ($rank === 'last') {
            $this->addMethod('sideEffect{ rank = it.out(***).count() - 1;}', $edgeName);
            $this->addMethod("out(***).filter{it.getProperty('rank')  == rank}", $edgeName);
        } else if ($rank === '2last') {
            $this->addMethod('sideEffect{ rank = it.out(***).count() - 2;}', $edgeName);
            $this->addMethod("out(***).filter{it.getProperty('rank')  == rank}", $edgeName);
        } else {
            $rank = abs(intval($rank));
            $this->addMethod("out(***).filter{it.getProperty('rank')  == ***}", $edgeName, $rank);
        }
        
        return $this;
    }

    public function nextSibling($link = 'ELEMENT') {
        $this->addMethod("sideEffect{sibling = it.rank}.in('$link').out('$link').filter{sibling + 1 == it.rank}");

        return $this;
    }

    public function nextSiblings($link = 'ELEMENT') {
        $this->addMethod("sideEffect{sibling = it.rank}.in('$link').out('$link').filter{sibling + 1 <= it.rank}");

        return $this;
    }

    public function previousSibling($link = 'ELEMENT') {
        $this->addMethod("filter{it.rank > 0}.sideEffect{sibling = it.rank}.in('$link').out('$link').filter{sibling - 1 == it.rank}");

        return $this;
    }

    public function previousSiblings($link = 'ELEMENT') {
        $this->addMethod("filter{it.rank > 0}.sideEffect{sibling = it.rank}.in('$link').out('$link').filter{sibling - 1 >= it.rank}");

        return $this;
    }
    
    public function inIs($edgeName) {
        if (is_array($edgeName)) {
            // @todo
            $this->addMethod('inE.filter{it.label in ***}.outV', $edgeName);
        } else {
            $this->addMethod('in(***)', $edgeName);
        }
        
        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function inIsIE($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod("transform{ a = it; while (a.in('$edgeName').any()) { a = a.in('$edgeName').next(); };  a;}", $edgeName);
        } else {
            $this->addMethod("transform{ a = it; while (a.in('$edgeName').any()) { a = a.in('$edgeName').next(); };  a;}", $edgeName);
        }
        
        return $this;
    }

    public function inIsnot($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod('filter{ it.inE.filter{ it.label in ***}.any() == false}', $edgeName);
        } else {
            $this->addMethod('filter{ it.in(***).any() == false}', $edgeName);
        }
        
        return $this;
    }

    public function hasIn($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod('filter{ it.inE.filter{ it.label in ***}.any()}', $edgeName);
        } else {
            $this->addMethod('filter{ it.in(***).any()}', $edgeName);
        }
        
        return $this;
    }

    public function raw($query) {
        $this->methods[] = $query;
        
        return $this;
    }
    
    public function hasNoIn($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod('filter{ it.inE.filter{ it.label in ***}.any() == false}', $edgeName);
        } else {
            $this->addMethod('filter{ it.in(***).any() == false}', $edgeName);
        }
        
        return $this;
    }

    public function hasOut($edgeName) {
        if (is_array($edgeName)) {
            $this->addMethod('filter{ it.outE.filter{ it.label in ***}.inV.any()}', $edgeName);
        } else {
            $this->addMethod('filter{ it.out(***).any()}', $edgeName);
        }
        
        return $this;
    }
    
    public function hasNoOut($edgeName) {
        if (is_array($edgeName)) {
            // @todo
            $this->addMethod('filter{ it.outE.filter{ it.label in ***}.inV.any() == false}', $edgeName);
        } else {
            $this->addMethod('filter{ it.out(***).any() == false}', $edgeName);
        }
        
        return $this;
    }

    public function isInCatchBlock() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Catch"}{(it.object.atom == "Catch")}.any()');
        
        return $this;
    }

    public function isNotInCatchBlock() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Catch"}{(it.object.atom == "Catch")}.any() == false}');
        
        return $this;
    }

    public function hasParent($parentClass, $ins = array()) {
        if (empty($ins)) {
            $in = '.in';
        } else {
            $in = array();
            
            if (!is_array($ins)) { $ins = array($ins); }
            foreach($ins as $i) {
                if (empty($i)) {
                    $in[] = '.in';
                } else {
                    $in[] = ".in('$i')";
                }
            }
            
            $in = implode('', $in);
        }
        
        if (is_array($parentClass)) {
            $this->addMethod('filter{ it.'.$in.'.filter{ it.atom in ***).count() != 0}', $parentClass);
        } else {
            $this->addMethod('filter{ it.'.$in.'.has("atom", ***).count() != 0}', $parentClass);
        }
        
        return $this;
    }

    public function hasNoParent($parentClass, $ins = array()) {
        
        if (empty($ins)) {
            $in = '.in';
        } else {
            $in = array();
            
            if (!is_array($ins)) { $ins = array($ins); }
            foreach($ins as $i) {
                if (empty($i)) {
                    $in[] = '.in';
                } else {
                    $in[] = ".in('$i')";
                }
            }
            
            $in = implode('', $in);
        }
        
        if (is_array($parentClass)) {
            $this->addMethod('filter{ it'.$in.'.filter{it.atom in ***}.count() == 0}', $parentClass);
        } else {
            $this->addMethod('filter{ it'.$in.'.has("atom", ***).count() == 0}', $parentClass);
        }
        
        return $this;
    }
    
    public function hasConstantDefinition() {
        $this->addMethod("filter{ g.idx('constants')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoConstantDefinition() {
        $this->addMethod("filter{ g.idx('constants')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function hasFunctionDefinition() {
        $this->addMethod("filter{ g.idx('functions')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoFunctionDefinition() {
        $this->addMethod("filter{ g.idx('functions')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function functionDefinition() {
        $this->addMethod("hasNot('fullnspath', null).transform{ g.idx('functions')[['path':it.fullnspath]].next(); }");
    
        return $this;
    }

    public function goToCurrentScope() {
        $this->addMethod('in.loop(1){!(it.object.atom in ["Function", "Phpcode"])}{(it.object.atom in ["Function", "Phpcode"])}');
        
        return $this;
    }
    
    public function goToFunction() {
        $this->addMethod('in.loop(1){it.object.atom != "Function"}{(it.object.atom == "Function") && (it.object.out("NAME").hasNot("code", "").any())}');
        
        return $this;
    }

    public function noNamespaceDefinition() {
        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('namespaces')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }

    public function classDefinition() {
        $this->addMethod("hasNot('fullnspath', null).transform{ g.idx('classes')[['path':it.fullnspath]].next(); }");
    
        return $this;
    }

    public function noClassDefinition() {
        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('classes')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }

    public function hasClassDefinition() {
        $this->addMethod("filter{ g.idx('classes')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoClassDefinition() {
        $this->addMethod("filter{ g.idx('classes')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function interfaceDefinition() {
        $this->addMethod("hasNot('fullnspath', null).transform{ g.idx('interfaces')[['path':it.fullnspath]].next(); }");
    
        return $this;
    }

    public function noInterfaceDefinition() {
        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('interfaces')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }

    public function hasInterfaceDefinition() {
        $this->addMethod("filter{ g.idx('interfaces')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoInterfaceDefinition() {
        $this->addMethod("filter{ g.idx('interfaces')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function traitDefinition() {
        $this->addMethod("hasNot('fullnspath', null).transform{ g.idx('traits')[['path':it.fullnspath]].next(); }");
    
        return $this;
    }

    public function noTraitDefinition() {
        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('traits')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }
    
    public function groupFilter($characteristic, $percentage) {
        $this->addMethod('sideEffect{'.$characteristic.'}.groupCount(gf){x2}.aggregate().sideEffect{'.$characteristic.'}.filter{gf[x2] < '.$percentage.' * gf.values().sum()}');

        return $this;
    }
    
    public function goToClass() {
        $this->addMethod('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}');
        
        return $this;
    }

    public function goToTrait() {
        $this->addMethod('in.loop(1){it.object.atom != "Trait"}{it.object.atom == "Trait"}');
        
        return $this;
    }

    public function goToClassTrait() {
        $this->addMethod('in.loop(1){!(it.object.atom in ["Trait","Class"])}{it.object.atom in ["Trait", "Class"]}');
        
        return $this;
    }
    
    public function goToExtends() {
        $this->addMethod('out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.atom == "Class"}');
        
        return $this;
    }

    public function goToImplements() {
        $this->addMethod('out("IMPLEMENTS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.atom == "Class"}');
        
        return $this;
    }

    public function goToAllParents() {
        $this->addMethod('out("IMPLEMENTS", "EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.atom == "Class"}');
        
        return $this;
    }

    public function goToTraits() {
        $this->addMethod('as("toTraits").out("BLOCK").out("ELEMENT").has("atom", "Use").out("USE").transform{ g.idx("traits")[["path":it.fullnspath]].next(); }.loop("toTraits"){true}{it.object.atom == "Trait"}');
        
        return $this;
    }

    public function hasFunction() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any()}');
        
        return $this;
    }

    public function hasNoFunction() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any() == false}');
        
        return $this;
    }

    public function hasClass() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.any()}');
        
        return $this;
    }

    public function hasNoClass() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.any() == false}');
        
        return $this;
    }

    public function hasTrait() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Trait"}{it.object.atom == "Trait"}.any()}');
        
        return $this;
    }

    public function hasNoTrait() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Trait"}{it.object.atom == "Trait"}.any() == false}');
        
        return $this;
    }

    public function goToMethodDefinition() {
        // starting with a staticmethodcall , no support for static, self, parent
        $this->addMethod('sideEffect{methodname = it.out("METHOD").next().code.toLowerCase();}
                .out("CLASS").transform{
                    if (it.code.toLowerCase() == "self") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next();
                    } else if (it.code.toLowerCase() == "static") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next();
                    } else  if (it.code.toLowerCase() == "parent") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next().out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.next();
                    } else {
                        init = g.idx("classes")[["path":it.fullnspath]].next();
                    };

                    find = null;
                    if (init.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any()) {
                        found = init.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("NAME").next().code.toLowerCase() == methodname }.next();
                    } else if (init.out("EXTENDS").any() == false) {
                        found = it;
                    } else {
                        found = init.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                            .loop(2){ it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any() == false}
                                    { it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any()}
                        .next();
                        
                        if (found == null) { found = it; } else {
                            found = found.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("NAME").next().code.toLowerCase() == methodname }.next();
                        }
                    };
                    found;
                }.has("atom", "Function")');
        
        return $this;
    }
    
    public function goToPropertyDefinition() {
        // starting with a staticproperty 
        $this->addMethod('sideEffect{ propertyname = it.out("PROPERTY").next().code.toLowerCase() }.out("CLASS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                .out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                .loop(2){ it.object.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").filter{ it.code.toLowerCase() == propertyname }.any() == false}
                        { it.object.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").filter{ it.code.toLowerCase() == propertyname }.any()}
                .out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{ it.out("DEFINE").code.toLowerCase() == methodname }');
        
        return $this;
    }

    public function goToNamespace() {
        $this->addMethod('in.loop(1){!(it.object.atom in ["Namespace", "File"])}{it.object.atom in ["Namespace", "File"]}');
        
        return $this;
    }

    public function fetchContext() {
        $this->addMethod('sideEffect{ context = ["Namespace":"Global", "Function":"Global", "Class":"Global"]; it.in.loop(1){true}{it.object.atom in ["Namespace", "Function", "Class"]}.each{ if (it.atom == "Namespace") { context[it.atom] = it.out("NAMESPACE").next().fullcode; } else { context[it.atom] = it.out("NAME").next().code; } } }');
        
        return $this;
    }
    
    public function followConnexion( $iterations = 3) {
        //it.rank in x[-2] should be better than !x[-2].intersect([it.rank]).isEmpty() but this isn't working!!
        $this->addMethod(
        <<<GREMLIN

// Loop init
sideEffect{ loops = 1;}

//// LOOP ////
.as('connexion')
.transform{ 
    if (it.in('METHOD').any() == false) { 
        if (g.idx('functions')[['path':it.fullnspath]].any()) {  
            g.idx('functions')[['path':it.fullnspath]].next(); 
        } else {
            it;
        } 
    } else if (it.in('METHOD').any()) {  // case of Staticmethodcall or Propertycall
                name = it.code.toLowerCase();  
                g.idx('atoms')[['atom':'Class']].out('BLOCK').out('ELEMENT')
                                    .has('atom', 'Function').out('NAME').filter{it.code.toLowerCase() == name }.next(); 
    } else { it; } 
}.in('NAME')
// calculating the path AND obtaining the arguments list
.sideEffect{ while(x.last() >= loops) { x.pop(); x.pop();}; y = it.out('ARGUMENTS').out('ARGUMENT').filter{!x[-2].intersect([it.rank]).isEmpty() }.code.toList(); x += [y];  x += loops;}
// find outgoing function
.out('BLOCK').out.loop(1){true}{it.object.atom in ['Functioncall', 'Staticmethodcall', 'Methodcall'] && (it.object.in('METHOD').any() == false)}
.transform{ if (it.out('METHOD').any()) { it.out('METHOD').next(); } else { it; }}

// filter with arguments that are relayed 
.filter{ it.out('ARGUMENTS').out('ARGUMENT').filter{ it.code in x[-2]}.any() }
.sideEffect{ y=[]; it.out('ARGUMENTS').out('ARGUMENT').filter{ it.code in x[-2]}.rank.fill(y); x += [y]; x += loops}

.loop('connexion'){ loops = it.loops; it.loops < $iterations; }{true}
//// LOOP ////

GREMLIN
                        );
        
        return $this;
    }
    
    public function makeVariable($variable) {
        $this->addMethod('sideEffect{ '.$variable.' = "\$" + '.$variable.' }');
        
        return $this;
    }
    
    public function run() {

        $this->analyze();
        $this->prepareQuery();

        $this->execQuery();
        
        return $this->rowCount;
    }
    
    public function getRowCount() {
        return $this->rowCount;
    }

    public function analyze() { return true; } 
    // @todo log errors when using this ? 

    public function printQuery() {
        $this->prepareQuery();
        
        foreach($this->queries as $id => $query) {
            print "$id)\n";
            print_r($query);
            print_r($this->queriesArguments[$id]);

            krsort($this->queriesArguments[$id]);
            
            foreach($this->queriesArguments[$id] as $name => $value) {
                if (is_array($value)) {
                    $query = str_replace($name, "['".implode("', '", $value)."']", $query);
                } elseif (is_string($value)) {
                    $query = str_replace($name, "'".str_replace('\\', '\\\\', $value)."'", $query);
                } elseif (is_int($value)) {
                    $query = str_replace($name, $value, $query);
                } else {
                    print 'Cannot process argument of type '.gettype($value)."\n";
                    die(__METHOD__);
                }
            }
            
            print $query;
            
            print "\n\n";
        }
        die();
    }

    public function prepareQuery() {
        // @doc This is when the object is a placeholder for others. 
        if (count($this->methods) == 1) { return true; }
        
        array_splice($this->methods, 2, 0, array('as("first")'));
        $query = implode('.', $this->methods);
        
        if ($this->methods[1] == 'has("atom", arg1)') {
            $query = "g.idx('atoms')[['atom':'{$this->arguments['arg1']}']].{$query}";
        } else {
            $query = "g.V.{$query}";
        }
        
        // search what ? All ? 
        $query = <<<GREMLIN

c = 0;
m = [:]; gf = [:];
n = [];
{$query}
GREMLIN;
        
        $query .= $this->apply->getGremlin();

    // initializing a new query 
        $this->queries[] = $query;
        $this->queriesArguments[] = $this->arguments;

        $this->methods = array();
        $this->arguments = array();
        $this->analyzerIsNot($this->analyzer);
        
        return true;
    }
    
    public function execQuery() {
        if (empty($this->queries)) { return true; }

        // @todo add a test here ? 
        foreach($this->queries as $id => $query) {
            $r = $this->query($query, $this->queriesArguments[$id]);
            $this->rowCount += $r[0][0];
        }

        // reset for the next
        $this->queries = array(); 
        $this->queriesArguments = array(); 
        
        // @todo multiple results ? 
        // @todo store result in the object until reading. 
        return $this->rowCount;
    }

    public function toCount() {
        return count($this->toArray());
    }
    
    public function toArray() {
        $queryTemplate = "g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']].out"; 
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->fullcode;
            }   
        } 
        
        return $report;
    }

    public function getArray() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        if (substr($analyzer, 0, 17) === 'Analyzer\\\\Files\\\\') {
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out.as('fullcode').as('line').as('filename').select{it.fullcode}{it.line}{it.fullcode}
GREMLIN;
        } else {
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out.as('fullcode').in.loop(1){ it.object.token != 'T_FILENAME'}.as('file').back('fullcode').as('line').select{it.fullcode}{it.line}{it.fullcode}
GREMLIN;
        }
        $vertices = $this->query($query);

        $analyzer = $this->analyzer;
        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = array('code' => $v[0][0], 
                                  'file' => $v[0][2], 
                                  'line' => $v[0][1], 
                                  'desc' => $this->getName());
            }   
        } 
        
        return $report;
    }

    public function toCountedArray($load = 'it.fullcode') {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out.groupCount(m){{$load}}.cap"; 
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0][0] as $k => $v) {
                $report[$k] = $v;
            }   
        } 
        
        return $report;
    }
    
    protected function loadIni($file) {
        $fullpath = dirname(dirname(__DIR__)).'/data/'.$file;
        
        if (!file_exists($fullpath)) {
            return null;
        } else {
            return parse_ini_file($fullpath);
        }
    }
    
    protected function loadConfig() {
        
    }
    
    public static function listAnalyzers() {
        $files = glob('library/Analyzer/*/*.php');

        $analyzers = array();
        foreach($files as $file) {
            $type = basename(dirname($file));
            if ($type == 'Common') { continue; }
            if ($type == 'Test') { continue; }
            if ($type == 'Group') { continue; }
            $analyzers[] = $type.'/'.substr(basename($file), 0, -4);
        }
        return $analyzers;
    }
    
    public function hasResults() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = "g.idx('analyzers')[['analyzer':'".$analyzer."']].out.count()"; 
        $vertices = $this->query($queryTemplate);
        
        return $vertices[0][0] > 0;
    }
    
    public function getSeverity() {
        if (Analyzer::$docs === null) {
            Analyzer::$docs = new Docs(dirname(dirname(dirname(__FILE__))).'/data/analyzers.sqlite');
        }
        
        return Analyzer::$docs->getSeverity($this->analyzer);
    }

    public function getFileList() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = "m=[:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out('ANALYZED').in.loop(1){true}{it.object.atom == 'File'}.groupCount(m){it.filename}.iterate(); m;"; 
        $vertices = $this->query($queryTemplate);
        
        $return = array();
        foreach($vertices->toArray() as $k => $v) {
            $return[$k] = $v[0];
        }
        
        return $return;
    }

    public function getVendors() {
        if (Analyzer::$docs === null) {
            Analyzer::$docs = new Docs(dirname(dirname(dirname(__FILE__))).'/data/analyzers.sqlite');
        }
        
        return Analyzer::$docs->getVendors();
    }

    public function getTimeToFix() {
        return $this->timeToFix;
    }

    public function getPhpversion() {
        return $this->phpVersion;
    }

    public function getphpConfiguration() {
        return $this->phpConfiguration;
    }
    
    public function makeFullNsPath($functions) {
        return array_map(function ($x) { return "\\".strtolower($x); },  $functions);
    }
}
?>
