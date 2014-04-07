<?php

namespace Analyzer;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

class Analyzer {
    protected $client = null;
    protected $code = null;
    protected $human_classname = null;

    protected $name = null;
    protected $description = null;
    protected $appinfo = null;
    
    protected $row_count = 0;

    private $apply_below = false;

    private $queries = array();
    private $queries_arguments = array();
    private $methods = array();
    private $arguments = array();
    
    static $analyzers = array();

    protected $phpversion = "Any";
    protected $phpconfiguration = "Any";

    protected $severity = \Analyzer\Analyzer::S_NONE; // Default to None. 
    const S_CRITICAL = "Critical";
    const S_MAJOR = "Major";
    const S_MINOR = "Minor";
    const S_NOTE = "Note";
    const S_NONE = "None";

    protected $timeToFix = \Analyzer\Analyzer::T_NONE; // Default to no time (Should not display)
    const T_NONE = "0";
    const T_INSTANT = "5";
    const T_QUICK = "30";
    const T_SLOW = "60";
    const T_LONG = "360";

    public function __construct($client) {
        $this->client = $client;
        $this->analyzerIsNot(get_class($this));

        $this->code = get_class($this);
        
        $this->human_classname = str_replace('\\', '/', substr(get_class($this), 9));
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
            return $class;
        } else {
            return false;
        }
    }
    
    public static function getSuggestionClass($analyzer) {
        $list = glob('library/Analyzer/*/*.php');
        $r = array();
        foreach($list as $id => $c) {
            $c = substr($c, 17, -4);
            $c = str_replace('/', '_', $c);
            $l = levenshtein($c, $analyzer);
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
        if (is_null($this->description)) {
            $filename = "./human/$lang/".str_replace("\\", "/", str_replace("Analyzer\\", "", get_class($this))).".ini";
            
            if (!file_exists($filename)) {
                $human = array();
            } else {
                $human = parse_ini_file($filename);
            }

            if (isset($human['description'])) {
                $this->description = $human['description'];
            } else {
                $this->description = "";
            }

            if (isset($human['name'])) {
                $this->name = $human['name'];
            } else {
                $this->name = get_class($this);
            }

            if (isset($human['appinfo'])) {
                $this->appinfo = $human['appinfo'];
            } else {
                $this->appinfo = get_class($this);
            }
        }
        
        return $this->description;
    }

    function getName($lang = 'en') {
        if (is_null($this->name)) {
            $this->getDescription($lang);
        }

        return $this->name;
    }

    function getThemes() {
        if (empty($this->themes)) {
            return array();
        } else {
            return $this->themes;
        }
    }

    function getAppinfoHeader($lang = 'en') {
        if (is_null($this->appinfo)) {
            $this->getDescription($lang);
        }

        return $this->appinfo;
    }
    
    static function getAnalyzers($theme) {
        return Analyzer::$analyzers[$theme];
    }

    private function addMethod($method, $arguments = null) {
        if (!is_null($arguments)) {
            if (func_num_args() > 2) {
                $arguments = func_get_args();
                array_shift($arguments);
                $argnames = array(str_replace('***', "%s", $method));
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
        } else {
            $this->methods[] = $method;
        }

        return $this;
    }
    
    public function init() {
        $result = $this->query("g.getRawGraph().index().existsForNodes('analyzers');");
        if ($result[0][0] == 'false') {
            $this->query("g.createManualIndex('analyzers', Vertex)");
        }
        
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $query = "g.idx('analyzers')[['analyzer':'$analyzer']]";
        $res = $this->query($query);
        
        if (isset($res[0]) && count($res[0]) == 1) {
            print "cleaning {$this->human_classname}\n";
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].outE('ANALYZED').each{
    g.removeEdge(it);
}

GREMLIN;
            $this->query($query);
        } else {
            print "new $analyzer\n";
            $this->code = addslashes($this->code);
            $query = <<<GREMLIN
x = g.addVertex(null, [analyzer:'$analyzer', analyzer:'true', description:'Analyzer index for $analyzer', code:'{$this->code}', fullcode:'{$this->code}',  atom:'Index', token:'T_INDEX']);

g.idx('analyzers').put('analyzer', '$analyzer', x);

g.V.has('token', 'E_CLASS')[0].each{     g.addEdge(it, x, 'CLASS'); }
g.V.has('token', 'E_FUNCTION')[0].each{     g.addEdge(it, x, 'FUNCTION'); }
g.V.has('token', 'E_NAMESPACE')[0].each{     g.addEdge(it, x, 'NAMESPACE'); }
g.V.has('token', 'E_FILE')[0].each{     g.addEdge(it, x, 'FILE'); }

GREMLIN;
            $this->query($query);
        }
    }

    public function checkPhpConfiguration($Php) {
        // this handles Any version of PHP
        if ($this->phpconfiguration == 'Any') {
            return true;
        }
        
        // @todo this must be updated.
        return true;
        die(__METHOD__);
        foreach($this->phpconfiguration as $ini => $value) {
            if (ini_get($ini) != $value) {
                return false;
            }
        }
        
        return false;
    }
    
    public function checkPhpVersion($version) {
        // this handles Any version of PHP
        if ($this->phpversion == 'Any') {
            return true;
        }

        // version and above 
        if ((substr($this->phpversion, -1) == '+') && version_compare($version, $this->phpversion) >= 0) {
            return true;
        } 

        // up to version  
        if ((substr($this->phpversion, -1) == '-') && version_compare($version, $this->phpversion) <= 0) {
            return true;
        } 

        // version range 1.2.3-4.5.6
        if (strpos($this->phpversion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpversion);
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
        } 
        
        // One version only
        if (version_compare($version, $this->phpversion) == 0) {
            return true;
        } 
        
        // Default behavior if we don't understand : 
        return false;
    }

    // @doc return the list of dependences that must be prepared before the execution of an analyzer
    // @doc by default, nothing. 
    function dependsOn() {
        return array();
    }
    
    function setApplyBelow($apply_below = true) {
        $this->apply_below = $apply_below;
        
        return $this;
    }

    public function query($queryString, $arguments = null) {
        if (is_null($arguments)) {
            $arguments = array('type' => 'IN');
        }

        try {
            $result = new \Everyman\Neo4j\Gremlin\Query($this->client, $queryString, $arguments);
            return $result->getResultSet();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
            print "Exception : ".$message."\n";
            
            print $queryString."\n";
            print_r($this->arguments);
            die();
        }
    }

    function _as($name) {
        $this->methods[] = 'as("'.$name.'")';
        
        return $this;
    }

    function back($name) {
        $this->methods[] = 'back("'.$name.'")';
        
        return $this;
    }

    function tokenIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{it.token in *** }', $atom);
        } else {
            $this->addMethod('has("token", ***)', $atom);
        }
        
        return $this;
    }

    function tokenIsNot($atom) {
        if (is_array($atom)) {
            $this->methods[] = 'filter{it.token not in [\''.join("', '", $atom).'\']}';
        } else {
            $this->methods[] = 'hasNot("token", "'.$atom.'")';
        }
        
        return $this;
    }
    
    function atomIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('filter{it.atom in ***}', $atom);
        } else {
            $this->addMethod('has("atom", ***)', $atom);
        }
        
        return $this;
    }

    function classIsNot($class) {
        if (is_array($class)) {
            $this->methods[] = 'as("classIsNot").inE("CLASS").filter{it.classname not in [\''.join("', '", $class).'\']}.back("classIsNot")';
        } else {
            $this->methods[] = 'as("classIsNot").inE("CLASS").hasNot("classname", "'.$class.'").back("classIsNot")';
        }
        
        return $this;
    }
    
    function functionIs($function) {
        if (is_array($function)) {
            $this->methods[] = 'as("functionIs").inE("FUNCTION").filter{it.function in [\''.join("', '", $function).'\']}.back("functionIs")';
        } else {
            $this->methods[] = 'as("functionIs").inE("FUNCTION").has("function", "'.$function.'").back("functionIs")';
        }
        
        return $this;
    }

    function functionIsNot($function) {
        if (is_array($function)) {
            $this->methods[] = 'as("functionIsNot").inE("FUNCTION").filter{it.function not in [\''.join("', '", $function).'\']}.back("functionIsNot")';
        } else {
            $this->methods[] = 'as("functionIsNot").inE("FUNCTION").hasNot("function", "'.$function.'").back("functionIsNot")';
        }
        
        return $this;
    }
    
    function classIs($class) {
        if (is_array($class)) {
            $this->methods[] = 'as("classIs").inE("CLASS").filter{it.classname in [\''.join("', '", $class).'\']}.back("classIs")';
        } else {
//            $this->methods[] = 'filter{it.inE("CLASS").classname == "'.$class.'"}';
// @note I don't understand why filter won,t work.
            $this->methods[] = 'as("classIs").inE("CLASS").has("classname", "'.$class.'").back("classIs")';
        }
        
        return $this;
    }

    function namespaceIs($namespace) {
        if (is_array($namespace)) {
            $this->methods[] = 'as("namespaceIs").inE("NAMESPACE").filter{it.namespace in [\''.join("', '", $namespace).'\']}.back("namespaceIs")';
        } else {
// @note I don't understand why filter won,t work.
            $this->methods[] = 'as("namespaceIs").inE("NAMESPACE").has("namespace", "'.$namespace.'").back("namespaceIs")';
        }
        
        return $this;
    }

    function atomInside($atom) {
        if (is_array($atom)) {
            $this->addMethod('as("loop").out().loop("loop"){true}{it.object.atom in ***}', $atom);
        } else {
            $this->addMethod('as("loop").out().loop("loop"){true}{it.object.atom == ***}', $atom);
        }
        
        return $this;
    }

    function noAtomInside($atom) {
        if (is_array($atom)) {
            die('can t run this yet');
            $this->addMethod('as("loop").out().loop("loop"){true}{it.object.atom in ***}', $atom);
        } else {
            $this->addMethod('filter{ it.as("loop").out().loop("loop"){true}{it.object.atom == ***}.count() == 0}', $atom);
        }
        
        return $this;
    }
    
    function trim($property, $chars = '\'\"') {
        $this->methods[] = 'transform{it.'.$property.'.replaceFirst("^['.$chars.']?(.*?)['.$chars.']?\$", "\$1")}';
    }

    function atomIsNot($atom) {
        if (is_array($atom)) {
            $this->methods[] = 'filter{!(it.atom in [\''.join("', '", $atom).'\']) }';
        } else {
            $this->methods[] = 'hasNot("atom", "'.$atom.'")';
        }
        
        return $this;
    }

    function analyzerIs($analyzer) {
        if (is_array($analyzer)) {
            $this->addMethod('filter{ it.in("ANALYZED").filter{ it.code in ***}.count() != 0}', $analyzer);
        } else {
            $this->addMethod('filter{ it.in("ANALYZED").has("code", ***).count() != 0}', $analyzer);
        }
        
        return $this;
    }

    function analyzerIsNot($analyzer) {
        $analyzer = str_replace('\\', '\\\\', $analyzer);

        if (is_array($analyzer)) {
            $this->methods[] = 'filter{ it.in("ANALYZED").filter{ not (it.code in [\''.join("', '", $analyzer).'\'])}.count() == 0}';
        } else {
            $this->methods[] = 'filter{ it.in("ANALYZED").has("code", \''.$analyzer.'\').count() == 0}';
        }

        return $this;
    }

    function is($property, $value= "'true'") {
        $this->methods[] = "filter{ it.$property == $value;}";

        return $this;
    }

    function isNot($property, $value= "'true'") {
        $this->methods[] = "hasNot('$property', $value)";
        
        return $this;
    }

    function hasOrder($value = "0") {
        if ($value == 'first') {
            $this->addMethod("has('order','0')");
        } elseif ($value == 'last') {
            $this->addMethod("filter{it.order == it.in('ELEMENT').out('ELEMENT').count() - 1}");
        } else {
            $this->addMethod("filter{it.order == ***}", abs(intval($value)));
        }

        return $this;
    }

    function code($code, $caseSensitive = false) {
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

    function codeIsNot($code, $caseSensitive = false) {
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

    function samePropertyAs($property, $name, $caseSensitive = false) {
        if ($caseSensitive) {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        $this->addMethod('filter{ it.'.$property.$caseSensitive.' == '.$name.$caseSensitive.'}');

        return $this;
    }

    function savePropertyAs($property, $name) {
        $this->methods[] = "sideEffect{ $name = it.$property }";

        return $this;
    }

    function fullcodeTrimmed($code, $trim = "\"'", $caseSensitive = false) {
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
            // @todo
            $this->methods[] = "filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") in ['".join("', '", $code)."']}";
        } else {
            $this->methods[] = "filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") == '$code'}";
        }
        
        return $this;
    }
    
    function fullcode($code, $caseSensitive = false) {
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
    
    function fullcodeIsNot($code, $caseSensitive = false) {
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
            // @todo
            $this->methods[] = "filter{!(it.fullcode$caseSensitive in ['".join("', '", $code)."'])}";
        } else {
            $this->methods[] = "filter{it.fullcode$caseSensitive != '$code'}";
        }
        
        return $this;
    }

    function fullcodeIsUppercase() {
        $this->methods[] = "filter{it.fullcode == it.fullcode.toUpperCase()}";
    }


    function filter($filter) {
        $this->methods[] = "filter{ $filter }";
    }

    function codeLength($length = " == 1 ") {
        // @todo add some tests ? Like Operator / value ? 
        $this->methods[] = "filter{it.code.length() $length}";
    }

    function fullcodeLength($length = " == 1 ") {
        // @todo add some tests ? Like Operator / value ? 
        $this->methods[] = "filter{it.fullcode.length() $length}";

        return $this;
    }

    function groupCount($column) {
        $this->methods[] = "groupCount(m){it.$column}";
        
        return $this;
    }

    function eachCounted($column, $times) {
        $this->methods[] = <<<GREMLIN
groupBy(m){it.$column}{it}.iterate(); 
m.findAll{ it.value.size() == $times}.values().flatten().each{ n.add(it); };
n
GREMLIN;

        return $this;
    }

    function regex($column, $regex) {
        $this->methods[] = <<<GREMLIN
filter{ (it.$column =~ "$regex" ).getCount() > 0 }
GREMLIN;

        return $this;
    }

    function regexNot($column, $regex) {
        $this->methods[] = <<<GREMLIN
filter{ (it.$column =~ "$regex" ).getCount() == 0 }
GREMLIN;

        return $this;
    }

    protected function outIs($edge_name) {
        if (is_array($edge_name)) {
            $this->addMethod("outE.filter{it.label in ***}.inV", $edge_name);
        } else {
            $this->addMethod("out(***)", $edge_name);
        }
        
        return $this;
    }

    function outIsnt($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
        } else {
            $this->addMethod("filter{ it.out(***).count() == 0}", $edge_name);
        }
        
        return $this;
    }

    function orderIs($edge_name, $order) {
        $order = intval($order);
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in orderIs()");
        } else {
            $this->addMethod("out(***).has('order', ***)", $edge_name, $order);
        }
        
        return $this;
    }

    function inIs($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            $this->addMethod("inE.filter{it.label in ***}.outV", $edge_name);
        } else {
            $this->addMethod("in(***)", $edge_name);
        }
        
        return $this;
    }

    function inIsnt($edge_name) {
        if (is_array($edge_name)) {
            die(" I don't understand arrays in inIsnot()");
            // @todo
        } else {
            $this->addMethod("filter{ it.in(***).count() == 0}", $edge_name);
        }
        
        return $this;
    }

    function hasIn($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
            $this->addMethod("filter{ it.inE.filter{ it.label in ***}.outV.count() == 0}", $edge_name);
        } else {
            $this->addMethod("filter{ it.in(***).count() != 0}", $edge_name);
        }
        
        return $this;
    }

    function raw($query) {
        $this->methods[] = $query;
        
        return $this;
    }
    
    function hasNoIn($edge_name) {
        if (is_array($edge_name)) {
            $this->addMethod("filter{ it.inE.filter{ it.label in ***}.count() == 0}", $edge_name);
        } else {
            $this->addMethod("filter{ it.in(***).count() == 0}", $edge_name);
        }
        
        return $this;
    }

    function hasOut($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
            $this->addMethod("filter{ it.outE.filter{ it.label in ***}.inV.count() == 0}", $edge_name);
        } else {
            $this->addMethod("filter{ it.out(***).count() != 0}", $edge_name);
        }
        
        return $this;
    }
    
    function hasNoOut($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            $this->addMethod("filter{ it.outE.filter{ it.label in ***}.inV.count() == 0}", $edge_name);
        } else {
            $this->addMethod("filter{ it.out(***).count() == 0}", $edge_name);
        }
        
        return $this;
    }
        
    public function hasParent($parent_class, $ins = array()) {
        if (empty($ins)) {
            $in = '.in';
        } else {
            $in = array();
            
            if (!is_array($ins)) { $ins = array($ins); }
            foreach($ins as $i) {
                if (empty($i)) {
                    $in[] = ".in";
                } else {
                    $in[] = ".in('$i')";
                }
            }
            
            $in = join('', $in);
        }
        
        if (is_array($parent_class)) {
            // @todo
            die(" I don't understand arrays in hasParent() ".__METHOD__);
        } else {
            $this->methods[] = 'filter{ it.'.$in.'.has("atom", "'.$parent_class.'").count() != 0}';
        }
        
        return $this;
    }

    public function hasNoParent($parent_class, $ins = array()) {
        
        if (empty($ins)) {
            $in = '.in';
        } else {
            $in = array();
            
            if (!is_array($ins)) { $ins = array($ins); }
            foreach($ins as $i) {
                if (empty($i)) {
                    $in[] = ".in";
                } else {
                    $in[] = ".in('$i')";
                }
            }
            
            $in = join('', $in);
        }
        
        if (is_array($parent_class)) {
            // @todo
            die(" I don't understand arrays in hasNoParent() ".__METHOD__);
        } else {
            $this->methods[] = 'filter{ it'.$in.'.has("atom", "'.$parent_class.'").count() == 0}';
        }
        
        return $this;
    }
    
    public function run() {

        $this->analyze();
        $this->prepareQuery();

        $this->execQuery();
        
        return $this->row_count;
    }
    
    public function getRowCount() {
        return $this->row_count;
    }

    public function analyze() { return true; } 
    // @todo log errors when using this ? 

    function printQuery() {
        $this->prepareQuery();
        
        foreach($this->queries as $id => $query) {
            print "$id)\n";
            print_r($query);
            print_r($this->queries_arguments[$id]);

            foreach($this->queries_arguments[$id] as $name => $value) {
                if (is_string($value)) {
                    $query = str_replace($name, "'".str_replace('\\', '\\\\', $value)."'", $query);
                } else {
                    $query = str_replace($name, "['".join("', '", $value)."']", $query);
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
        $query = join('.', $this->methods);
        
        // search what ? All ? 
        $query = <<<GREMLIN

c = 0;
m = [:];
n = [];
g.V.{$query}
GREMLIN;

        // Indexed results
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        if ($this->apply_below) {
            $apply_below = <<<GREMLIN
x = it;
it.in("VALUE").            out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('KEY').in("VALUE").  out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('VALUE').in("VALUE").out('LOOP').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }
it.in('ARGUMENT').in("ARGUMENTS").out('BLOCK').out.loop(1){it.loops < 100}{it.object.code == x.code}.each{ g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED'); }

GREMLIN;
        } else {
            $apply_below = "";
        }
        $query .= <<<GREMLIN
.each{
    g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED');
    
    // Apply below
    {$apply_below}
    
    c = c + 1;
}
c;

GREMLIN;

        $this->queries[] = $query;
        $this->queries_arguments[] = $this->arguments;

        $this->methods = array();
        $this->arguments = array();
        $this->analyzerIsNot(addslashes(get_class($this)));
        
        return true;
    }
    
    public function execQuery() {
        if (empty($this->queries)) { return true; }

        // @todo add a test here ? 
        foreach($this->queries as $id => $query) {
            $r = $this->query($query, $this->queries_arguments[$id]);
            $this->row_count += $r[0][0];
        }

        // reset for the next
        $this->queries = array(); 
        $this->queries_arguments = array(); 
        
        // @todo multiple results ? 
        // @todo store result in the object until reading. 
        return $this->row_count;
    }

    public function toCount() {
        return count($this->toArray());
    }
    
    public function toArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "g.idx('analyzers')[['analyzer':'".$analyzer."']].out"; 
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->fullcode;
            }   
        } 
        
        return $report;
    }

    public function toFullArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out.as('fullcode').in('FILE').as('file').back('fullcode').as('line').select{it.fullcode}{it.line}{it.fullcode}
GREMLIN;
        $vertices = $this->query($queryTemplate);

        $analyzer = get_class($this);
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

    public function toCountedArray($load = "it.fullcode") {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
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
    
    public static function listAnalyzers() {
        $files = glob('library/Analyzer/*/*.php');

        $analyzers = array();
        foreach($files as $file) {
            $type = basename(dirname($file));
            if ($type == 'Common') { continue; }
            if ($type == 'Test') { continue; }
            if ($type == 'Group') { continue; }
            if ($type == 'Themes') { continue; }
            $analyzers[] = $type.'/'.substr(basename($file), 0, -4);
        }
        return $analyzers;
    }
    
    public function hasResults() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "g.idx('analyzers')[['analyzer':'".$analyzer."']].out.count()"; 
        $vertices = $this->query($queryTemplate);
        
        return $vertices[0][0] > 0;
    }
    
    public function getSeverity() {
        return $this->severity;
    }

    public function getTimeToFix() {
        return $this->timeToFix;
    }

    public function getPhpversion() {
        return $this->phpversion;
    }

    public function getPhpconfiguration() {
        return $this->phpconfiguration;
    }
}
?>