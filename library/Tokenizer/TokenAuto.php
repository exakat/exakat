<?php

namespace Tokenizer;

class TokenAuto extends Token {
    static public $round = -1;
    protected $conditions = array();
    protected $set_atom   = false;
    public $total  = null ;
    public $done  = null ;

    public function _check() {
        return false;
    }
    
    public function prepareQuery() {
        $query = " total = 0; done = 0; ";
        $class = str_replace("Tokenizer\\", '', get_class($this));
        if (in_array($class, array('FunctioncallArray'))) {
            $query .= "g.idx('racines')[['token':'S_ARRAY']].out('INDEXED')";
        } elseif (in_array($class, Token::$types)) {
            $query .= "g.idx('racines')[['token':'$class']].out('INDEXED')";
        } else {
            $query .= "g.V";
            print "Using g.V : $class\n";
        }
        $query .= ".sideEffect{ total++; }";

        $qcdts = array();
        
        if (!empty($this->conditions[0])) {
            $qcdts = array_merge($qcdts, $this->readConditions($this->conditions[0]));
            
            $qcdts[] = "as('origin')";
            unset($this->conditions[0]);
        }

        for($i = -8; $i < 0; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['previous'] = abs($i);
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
            unset($this->conditions[$i]);
        }

        for($i = 1; $i < 12; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['next'] = $i;
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
            unset($this->conditions[$i]);
        }
        
        if (!empty($this->conditions)) {
            print "Some condition were not used! \n".__METHOD__."\n";
            die();
        }
        
        $query = $query.".".join('.', $qcdts);
        
        $this->set_atom = false;
        $qactions = $this->readActions($this->actions);
        $query .= ".each{\n done++; fullcode = it; fullcode.round = ".(self::$round).";
".join(";\n", $qactions)."; ".($this->set_atom ? $this->fullcode() : '' )."\n}; [total:total, done:done];";
        
        return $query;
    }

    public function printQuery() {
        $query = $this->prepareQuery();
        
        print $query;
        die(__METHOD__);
    }

    public function checkAuto() {
        $this->total = null;

        $res = Token::query($this->prepareQuery());
        
        $this->total += (int) $res['total'][0];
        $this->done += (int) $res['done'][0];
        
        return $res;
    }

    private function readActions($actions) {
        $qactions = array();

        // @doc audit trail track
        $qactions[] = "\n it.setProperty('modifiedBy', '".str_replace('Tokenizer\\', '', get_class($this))."'); \n";

        if (isset($actions['keepIndexed'])) {
            if(!$actions['keepIndexed']) {
                $qactions[] = " 
/* Remove index links */  it.inE('INDEXED').each{ g.removeEdge(it); }
                ";
            }
            unset($actions['keepIndexed']);
        } else {
                $qactions[] = " 
/* Remove index links */  it.inE('INDEXED').each{ g.removeEdge(it); }
                ";
        }

        if (isset($actions['cleansemicolon']) && $actions['cleansemicolon']) {
            $qactions[] = "
/* cleansemicolon */
    it.out('NEXT').has('code',';').has('atom', null).each{ 
        g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
        g.removeEdge(it.inE('NEXT').next());
        g.removeEdge(it.outE('NEXT').next());

        g.removeVertex(it);
    }";
            unset($actions['cleansemicolon']);
        }

         if (isset($actions['transfert'])) {
            list(, $where) = each($actions['transfert']);
            $next = str_repeat(".out('NEXT')", $where);
            $qactions[] = " 
/* transfert property root away  */  
it.has('root', 'true')$next.each{ 
    it.setProperty('root', 'true');
    it.setProperty('test', 'true');
}
it.setProperty('root', 'null');
                ";
            unset($actions['transfert']);
        }                        

        if (isset($actions['atom'])) {
            if (is_string($actions['atom'])) {
                $qactions[] = " /* atom */\n   it.setProperty('atom', '".$actions['atom']."')";
            } elseif (is_int($actions['atom'])) {
                $qactions[] = " /* atom */\n  it.setProperty('atom', it.out('NEXT').next().atom)";
            }
            
            $qactions[] = " /* indexing */\n  g.idx('atoms').put('atom', '{$actions['atom']}', it);";
            
            unset($actions['atom']);
            $this->set_atom = true;
        }

        if (isset($actions['atom1'])) {
            $qactions[] = " /* atom1 */\n   it.out('NEXT').next().setProperty('atom', '".$actions['atom1']."');
            it.out('NEXT').next().setProperty('fullcode', it.out('NEXT').next().code);
            ";
            unset($actions['atom1']);
        }
        
        if (isset($actions['property'])) {
            if (is_array($actions['property']) && !empty($actions['property'])) {
                foreach($actions['property'] as $name => $value) {
                    $qactions[] = " /* property */   it.setProperty('$name', '$value')";
                }
            }
            unset($actions['property']);
        }

        if (isset($actions['propertyNext'])) {
            if (is_array($actions['propertyNext']) && !empty($actions['propertyNext'])) {
                $qactions[] = " /* propertyNext */   
fullcode = it.out('NEXT').next(); \n";
                foreach($actions['propertyNext'] as $name => $value) {
                    if (substr($value, 0, 3) == 'it.') {
                        $value = 'fullcode.' . substr($value, 3);
                    } else {
                        $value = "'$value'";
                    }
                    $qactions[] .= "
fullcode.setProperty('$name', $value)";
                }
            }
            unset($actions['propertyNext']);
        }
        
        if (isset($actions['rank']) && is_array($actions['rank'])) {
            foreach($actions['rank'] as $offset => $rank) {
                if ($offset > 0) {
                    $d = 'a'.$offset;
                } elseif ($offset < 0) {
                    $d = 'b'.abs($offset);
                } else {
                    $d = '';
                }
                $qactions[] = " /* rank */ $d.setProperty('rank', $rank);";
            }
            unset($actions['rank']);
        }        

        if (isset($actions['add_void'])) {
            foreach($actions['add_void'] as $destination => $label) {
                if ($destination > 0) {
                    $d = str_repeat(".out('NEXT')", $destination).".next()";
                } elseif ($destination < 0) {
                    $d = str_repeat(".in('NEXT')", $destination).".next()";
                } else {
                    $d = '';
                }
                $qactions[] = "
/* add void out ($destination) */
x = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

g.addEdge(it$d, x, '$label');

";
             }
             unset($actions['add_void']);
        }

        if (isset($actions['to_var_new'])) {
            $ppp = new _Ppp(Token::$client);
            $fullcode = $ppp->fullcode();
            
            $atom = $actions['to_var_new'];
            $qactions[] = "
/* to var with arguments or not */
var = it;
arg = it.out('NEXT').next();

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);
token = it.token;

root = g.addVertex(null, [code:'Sequence', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line, fullcode:';']);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), root, 'INDEXED');   

arg.out('ARGUMENT').filter{it.atom in ['Variable']}.each{
    ppp = g.addVertex(null, [code:'ppp', atom:'Ppp', token:token, virtual:true, line:it.line, fullcode:'ppp (to_var_new)']);
    g.idx('atoms').put('atom','Ppp', ppp);

    var.out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{
        option = g.addVertex(null, [code:it.code, fullcode:it.code, atom:it.atom, token:it.token, virtual:true, line:it.line]);
        g.addEdge(ppp, option, it.code.toUpperCase());
    }

    g.addEdge(root, ppp, 'ELEMENT');
    ppp.setProperty('rank', it.rank);

    g.addEdge(ppp, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

    g.addEdge(ppp, tvoid, 'VALUE');

    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line, fullcode: var.code]);
    g.addEdge(ppp, tstatic, var.code.toUpperCase());

    fullcode = ppp;
    $fullcode
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    ppp = g.addVertex(null, [code:'ppp', atom:'Ppp', token:token, virtual:true, line:it.line, fullcode: var.code]);
    var.out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{
        option = g.addVertex(null, [code:it.code, fullcode:it.code, atom:it.atom, token:it.token, virtual:true, line:it.line]);
        g.addEdge(ppp, option, it.code.toUpperCase());
    }
    g.idx('atoms').put('atom','Ppp', ppp);

    ppp.setProperty('rank', it.rank);
    g.addEdge(root, ppp, 'ELEMENT');

    g.addEdge(ppp, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(ppp, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line, fullcode:var.code]);
    g.addEdge(ppp, tstatic, var.code.toUpperCase());
    
    g.idx('delete').put('node', 'delete', it);

    fullcode = ppp;
    $fullcode
}

g.addEdge(root, arg.out('NEXT').next(), 'NEXT');
g.addEdge(var.in('NEXT').next(), root, 'NEXT');

var.bothE('NEXT').each{ g.removeEdge(it);}
arg.outE('NEXT').each{ g.removeEdge(it);}

arg.out('ARGUMENT').out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{ g.removeVertex(it); }
arg.out('ARGUMENT').outE('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{ g.removeEdge(it); }

var.out('STATIC', 'PRIVATE', 'PUBLIC', 'PROTECTED').each{ g.removeVertex(it); }
var.outE('STATIC', 'PRIVATE', 'PUBLIC', 'PROTECTED').each{ g.removeEdge(it); }

g.removeVertex(var);
g.removeVertex(arg);

";
            unset($actions['to_var_new']);
        }

        if (isset($actions['to_var'])) {
            $ppp = new _Ppp(Token::$client);
            $fullcode = $ppp->fullcode();

            $atom = $actions['to_var'];
            $qactions[] = "
/* to var with arguments */
var = it;
arg = it.out('NEXT').next();

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').filter{it.atom in ['Variable', 'Static', 'Ppp']}.each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, virtual:true, line:it.line, fullcode:var.code]);

    fullcode = ppp;
    $fullcode
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

    g.addEdge(x, tvoid, 'VALUE');
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, virtual:true, line:it.line, fullcode:var.code]);

    fullcode = ppp;
    $fullcode
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(x, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    g.idx('delete').put('node', 'delete', it);   
}

g.addEdge(root, var.out('NEXT').out('NEXT').next(), 'NEXT');
g.removeEdge(var.out('NEXT').outE('NEXT').next());
g.removeVertex(arg);

g.addEdge(var.in('NEXT').next(), var.out('NEXT').next(), 'NEXT');
var.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(var);

";
            unset($actions['to_var']);
        }

        if (isset($actions['to_global'])) {
            $_global = new _Global(Token::$client);
            $fullcode = $_global->fullcode();

            $sequence = new Sequence(Token::$client);
            $sequence_fullcode = $sequence->fullcode();

            $atom = $actions['to_global'];
            $qactions[] = "
/* to global without arguments */
var = it;
arg = it.out('NEXT').next();

var.setProperty('code', var.code);
var.setProperty('token', var.token);

c = -1;
arg.out('ARGUMENT').each{
    c = c + 1;
    x = g.addVertex(null, [code:'global', atom:'Global', token:'T_GLOBAL', virtual:true, line:it.line, rank:c]);

    g.addEdge(var, x, 'ELEMENT');
    g.addEdge(x,  it, 'NAME');
    
    it.inE('ARGUMENT').each{ g.removeEdge(it); }

    fullcode = x;
    $fullcode
}

g.addEdge(var, var.out('NEXT').out('NEXT').next(), 'NEXT');
g.removeEdge(var.out('NEXT').outE('NEXT').next());
g.removeVertex(arg);

var.setProperty('code', ';');
var.setProperty('atom', 'Sequence');
var.setProperty('token', 'T_GLOBAL');
var.inE('INDEXED').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'Sequence']].next(), var, 'INDEXED');   

fullcode = var;
$sequence_fullcode

";
            unset($actions['to_global']);
        }

        if (isset($actions['to_var_ppp'])) {
            $ppp = new _Ppp(Token::$client);
            $fullcode = $ppp->fullcode();

            list($atom1, $atom2) = $actions['to_var_ppp'];
            $qactions[] = "
/* to var with another ppp before (private static or static private) */

var = it;
arg = it.out('NEXT').next();
arg2 = it.in('NEXT').next();
token = it.token;

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').filter{ it.atom in ['Variable']}.each{
    ppp = g.addVertex(null, [code:'var', atom:'Ppp', token:token, virtual:true, line:it.line]);
    g.idx('atoms').put('atom','Ppp', ppp);

    fullcode = ppp;
    $fullcode
    
    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

    g.addEdge(ppp, tvoid, 'VALUE');
    
    atom1 = g.addVertex(null, [code:var.code, atom:'$atom1', token:var.token, virtual:true, line:it.line, fullcode:var.code]);
    g.addEdge(ppp, atom1, var.code.toUpperCase());
    
    atom2 = g.addVertex(null, [code:arg2.code, atom:'$atom2', token:arg2.token, virtual:true, line:it.line, fullcode:arg2.code]);
    g.addEdge(ppp, atom2, arg2.code.toUpperCase());
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    ppp = g.addVertex(null, [code:'var', atom:'Ppp', token:token, virtual:true, line:it.line]);
    g.idx('atoms').put('atom','Ppp', ppp);
    fullcode = ppp;
    $fullcode
    
    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(ppp, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    atom1 = g.addVertex(null, [code:var.code, atom:'$atom1', token:var.token, virtual:true, line:it.line, fullcode:var.code]);
    g.addEdge(ppp, atom1, var.code.toUpperCase());
    
    atom2 = g.addVertex(null, [code:arg2.code, atom:'$atom2', token:arg2.token, virtual:true, line:it.line, fullcode:arg2.code]);
    g.addEdge(ppp, atom2, arg2.code.toUpperCase());
    
    g.idx('delete').put('node', 'delete', it);   
}
g.addEdge(root, var.out('NEXT').out('NEXT').next(), 'NEXT');
g.removeEdge(var.out('NEXT').outE('NEXT').next());
g.removeVertex(arg);

g.addEdge(var.in('NEXT').in('NEXT').next(), var.out('NEXT').next(), 'NEXT');
var.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(var);

arg2.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(arg2);

";
            unset($actions['to_var_ppp']);
        }

        if (isset($actions['to_use_const'])) {
            $qactions[] = "
/* to use with const or function */

    if (a1.token == 'T_CONST') {
        link = 'CONST';
    } else {
        link = 'FUNCTION';
    }
    end = a2.out('NEXT').next();

    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a2.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
    g.addEdge(it, end, 'NEXT');
    g.addEdge(it, a2, link);
    
    g.idx('delete').put('node', 'delete', a1);   

";
            unset($actions['to_use_const']);
        }

        if (isset($actions['to_use'])) {
            $qactions[] = "
/* to use with arguments */
if (it.out('NEXT').next().token in ['T_CONST', 'T_FUNCTION']) {
    
    extra = it.out('NEXT').next();
    if (extra.token == 'T_CONST') {
        link = 'CONST';
    } else {
        link = 'FUNCTION';
    }
    arg = extra.out('NEXT').next();
    
    it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
    g.addEdge(it, arg, 'NEXT');
    
    g.idx('delete').put('node', 'delete', extra);   
} else {
    link = 'USE';
}

var = it;
arg = it.out('NEXT').next();

var.out('NEXT').has('atom', 'Arguments').out('ARGUMENT').each{
    g.addEdge(var, it, link);
    g.removeEdge(it.inE('ARGUMENT').next());
}

d = it.out('NEXT').out('NEXT').next();
it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(var, d, 'NEXT');
g.idx('delete').put('node', 'delete', arg);   

";
            unset($actions['to_use']);
        }

        if (isset($actions['to_use_block'])) {
            $qactions[] = "
/* to use with arguments and block */
var = it;
arg = it.out('NEXT').next();

arg.out('ARGUMENT').each{
    g.addEdge(var, it, 'USE');
    g.removeEdge(it.inE('ARGUMENT').next());
}

block = it.out('NEXT').out('NEXT').next();
next = block.out('NEXT').next();

block.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(var, block, 'BLOCK');

g.idx('delete').put('node', 'delete', arg);   
it.outE('NEXT').each{ g.removeEdge(it); }

g.addEdge(var, next, 'NEXT');

";
            unset($actions['to_use_block']);
        }
        if (isset($actions['to_lambda'])) {
            $qactions[] = "
/* to to_lambda function */

x = g.addVertex(null, [code:'', atom:'String', token:'T_STRING', virtual:true, line:it.line, fullcode:'']);

g.addEdge(it, x, 'NAME');
it.setProperty('lambda', 'true');

op = it.out('NEXT').next();
cp = it.out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(it, it.out('NEXT').out('NEXT').next(), 'ARGUMENTS');
block = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();
g.addEdge(it, block, 'BLOCK');

g.addEdge(it, block.out('NEXT').next(), 'NEXT');

g.removeEdge(block.outE('NEXT').next());

op.bothE('NEXT').each{ g.removeEdge(it); }
cp.bothE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', op);   
g.idx('delete').put('node', 'delete', cp);   

";
            unset($actions['to_lambda']);
        }

        if (isset($actions['to_lambda_use'])) {
            $qactions[] = "
/* to to_lambda function with use */

x = g.addVertex(null, [code:'', atom:'String', token:'T_STRING', virtual:true, line:it.line, fullcode:'']);

g.addEdge(it, x, 'NAME');
it.setProperty('lambda', 'true');

x = it.out('NEXT').next();
g.idx('delete').put('node', 'delete', x);   
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, x, 'ARGUMENTS');

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', x);   

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', x);   

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', x);   

x = x.out('NEXT').next();
g.addEdge(it, x, 'USE');

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', x);   

x = x.out('NEXT').next();
g.addEdge(it, x, 'BLOCK');

x = x.out('NEXT').next();
g.removeEdge(x.inE('NEXT').next());
g.addEdge(it, x, 'NEXT');   

";
            unset($actions['to_lambda_use']);
        }

        if (isset($actions['to_ppp'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "

/* to ppp alone */
x = g.addVertex(null, [code:it.code, atom:'Ppp', token:it.token, virtual:true, line:it.line, fullcode:it.code ]);

/* indexing */
g.idx('atoms').put('atom', 'Ppp', x);

g.addEdge(x, it.out('NEXT').next(), 'DEFINE');
it.out('NEXT').has('atom', 'Variable').each {
    tvoid = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

    g.addEdge(x, tvoid, 'VALUE');
}
g.addEdge(x, it, it.code.toUpperCase());
it.fullcode = it.code;

it.out('STATIC', 'PUBLIC', 'PROTECTED', 'PRIVATE').each{
    t = it.inE('STATIC', 'PUBLIC', 'PROTECTED', 'PRIVATE').next().label;
    g.removeEdge(it.inE(t).next());
    g.addEdge(x, it, t);
}

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

variable = it.out('NEXT').next();
variable.bothE('NEXT').each{ g.removeEdge(it); }
variable.bothE('INDEXED').each{ g.removeEdge(it); }

g.removeEdge( it.inE('NEXT').next());

fullcode = x;

";
            unset($actions['to_ppp']);
        }        

        if (isset($actions['to_ppp2'])) {
            $ppp = new _Ppp(Token::$client);
            $fullcode = $ppp->fullcode();

            $qactions[] = "
/* to ppp already ppp */

g.addEdge(it.out('NEXT').next() , it, it.code.toUpperCase());

p = it.in('NEXT').next();
ppp = it.out('NEXT').next();

it.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(p, ppp, 'NEXT');
it.fullcode = it.code;

fullcode = ppp;
$fullcode

";
            unset($actions['to_ppp2']);
        }        

        if (isset($actions['to_option'])) {
            $position = str_repeat(".out('NEXT')", $actions['to_option']);

            $ppp = new _Ppp(Token::$client);
            $fullcode = $ppp->fullcode();
            
            $qactions[] = "
/* turn the current token to an option of one of the next tokens (default 1)*/

ppp = it{$position}.next();

g.addEdge(ppp, it, it.code.toUpperCase());
g.addEdge(it.in('NEXT').next() , it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it); }
it.fullcode = it.code;

fullcode = ppp;
$fullcode
";
            unset($actions['to_option']);
        }    
        
        if (isset($actions['to_ppp_assignation'])) {
            $qactions[] = "
/* to ppp with assignation */

x = g.addVertex(null, [code:it.code, atom:'Ppp', token:it.token, virtual:true, line:it.line]);

it.out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{
    it.inE('STATIC', 'PRIVATE', 'PUBLIC', 'PROTECTED').each{ g.removeEdge( it ); }
    g.addEdge(x, it, it.code.toUpperCase());
}

g.addEdge(x, it.out('NEXT').out('LEFT').next(), 'DEFINE');
g.addEdge(x, it.out('NEXT').out('RIGHT').next(), 'VALUE');
g.addEdge(x, it, it.code.toUpperCase());
it.fullcode = it.code;

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

assignation = it.out('NEXT').next();
assignation.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(assignation);

g.removeEdge( it.inE('NEXT').next());

/* indexing */
g.idx('atoms').put('atom', 'Ppp', x);

fullcode = x;
";
            unset($actions['to_ppp_assignation']);
        }
        
        if (isset($actions['transform'])) {
            $c = 0; 
            
            foreach($actions['transform'] as $destination => $label) {
                if ($label == 'NONE') { continue; }

                // Destination > 0
                if ($destination > 0) { 
                    $c++;
                
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop out ($c) */
g.addEdge(a$c.in('NEXT').next(), a$c.out('NEXT').next(), 'NEXT');
a$c.bothE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', a$c);

";
                    } else {
                        $qactions[] = "
/* transform out ($c) */

g.addEdge(it, a$c, '$label');
g.addEdge(it, a$c.out('NEXT').next(), 'NEXT');
a$c.bothE('NEXT').each{ g.removeEdge(it); }
";
                    }

                // Destination < 0
                } elseif ($destination < 0) {
                    $d = abs($destination);
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop in ($c) */

g.addEdge(b$d.in('NEXT').next(), b$d.out('NEXT').next(), 'NEXT');
b$d.bothE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', b$d);
";
                    } else {
                        $qactions[] = "
/* transform in (-$c) */

g.addEdge(it, b$d, '$label');
g.addEdge(b$d.in('NEXT').next(), it, 'NEXT');
b$d.bothE('NEXT').each{ g.removeEdge(it); }
";
                    }

                // Destination == 0
                } else {
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop in (0) */
a = it.in('NEXT').next();
b = it.out('NEXT').next();

it.bothE('NEXT').each{    g.removeEdge(it); } 

g.removeVertex(it);
g.addEdge(a, b, 'NEXT');
";
                        $this->set_atom = false;
                    } else {
                        die("Destination 0 for transform\n");
                    }
                }
            }

            unset($actions['transform']);
        }

        if (isset($actions['arg2implement'])) {
            // must be after transform
            $qactions[] = "
/* Move arguments to implements */

if (it.out('IMPLEMENTS').out('ARGUMENT').any()) {
    classe = it;
    impl = it.out('IMPLEMENTS').next();
    impl.out('ARGUMENT').each{
        g.addEdge(classe, it, 'IMPLEMENTS');
    }

    impl.outE('ARGUMENT').{ g.removeEdge(it); }
    g.removeVertex(impl);
}

";
            unset($actions['arg2implement']);
        }
        

        if (isset($actions['to_const_assignation'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* to const with arguments or not */

assignation = it.out('NEXT').next();
g.addEdge(it, assignation.out('LEFT').next(), 'NAME');
g.addEdge(it, assignation.out('RIGHT').next(), 'VALUE');
g.removeEdge(assignation.outE('LEFT').next());
g.removeEdge(assignation.outE('RIGHT').next());

g.addEdge(it, assignation.out('NEXT').next(), 'NEXT');

assignation.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(assignation);

"; 
            unset($actions['to_const_assignation']);
        }
        
        if (isset($actions['to_const'])) {
            $sequence = new Sequence(Token::$client);
            $fullcode_sequence = $sequence->fullcode();
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* transform to const a=1 ,  b=2 => const a=1; const b=2 */

sequence = g.addVertex(null, [code:';', fullcode:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), sequence, 'INDEXED');   

fullcode = sequence;
$fullcode_sequence

_const = it;
arg = _const.out('NEXT').next(); 

g.addEdge(_const.in('NEXT').next(), sequence, 'NEXT');
g.addEdge(sequence, _const.out('NEXT').out('NEXT').out('NEXT').next(), 'NEXT');
g.idx('delete').put('node', 'delete', _const.out('NEXT').out('NEXT').next());   

g.removeEdge(_const.out('NEXT').out('NEXT').outE('NEXT').next());
g.removeEdge(_const.out('NEXT').outE('NEXT').next());
_const.bothE('NEXT').each{ g.removeEdge(it); }

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    x = g.addVertex(null, [code:'const', atom:'Const', token:'T_CONST', virtual:true, line:it.line]);
    x.setProperty('rank', it.rank);

    fullcode = x;
    
    g.addEdge(sequence, x, 'ELEMENT');

    /* indexing */
    g.idx('atoms').put('atom', 'Const', x);

    g.addEdge(x, it.out('LEFT').next(), 'NAME');
    g.addEdge(x, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    g.removeEdge(it.inE('ARGUMENT').next());

    $fullcode
    
    g.idx('delete').put('node', 'delete', it);   

}

g.idx('delete').put('node', 'delete', arg);   
g.idx('delete').put('node', 'delete', it);   

"; 
            unset($actions['to_const']);
}

        if (isset($actions['createSequenceForCaseWithoutSemicolon'])) {
            $sequence = new Sequence(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = "

/* createSequenceForCaseWithoutSemicolon */ 
x = g.addVertex(null, [code:'Block with Sequence For Case Without Semicolon', fullcode:'Block with Sequence For Case Without Semicolon', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');   

fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').out('NEXT').next();
b = a.out('NEXT').next();
rank = 0;

if (a.out('ELEMENT').count() > 0) {
    a.out('ELEMENT').each{
        it.setProperty('rank', it.rank); // this is in case b is also a sequence. May be a is also a sequence....
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
        rank++;
    }
    
    g.idx('delete').put('node', 'delete', a);
} else {
    rank = 1;
    a.setProperty('rank', 0);
    g.addEdge(x, a, 'ELEMENT');
}

if (b.out('ELEMENT').count() > 0) {
    b.out('ELEMENT').each{
        it.setProperty('rank', it.rank + rank); 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    }
    
    g.idx('delete').put('node', 'delete', b);
} else {
    b.setProperty('rank', 1);
    g.addEdge(x, b, 'ELEMENT');
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, b.out('NEXT').next(), 'NEXT');

a.setProperty('rank', 0);
b.setProperty('rank', 1);

a.bothE('NEXT').each{ g.removeEdge(it) ; }
b.outE('NEXT').each{ g.removeEdge(it) ; }
";
            unset($actions['createSequenceForCaseWithoutSemicolon']);
        }        

        if (isset($actions['makeNamespace'])) {
            $qactions[] = " 
/* makeNamespace */  

p = it;
nsname = it;
nsname.setProperty('atom', 'Nsname');
rank = 0;

subname = p.in('NEXT').next();
if (subname.getProperty('token') in ['T_STRING', 'T_NAMESPACE']) {
    g.addEdge(nsname, subname, 'SUBNAME');
    subname.setProperty('rank', rank++);
    subname.has('token', 'T_NAMESPACE').each{ it.setProperty('fullcode', it.code); }
    
    p2 = subname.in('NEXT').next();
    subname.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
    g.addEdge(p2, nsname, 'NEXT');
    p.setProperty('absolutens', 'false');
    
} else {
    rank = 0;
    p.setProperty('absolutens', 'true');
}

while(p.getProperty('token') == 'T_NS_SEPARATOR') {
    subname = p.out('NEXT').next();
    g.addEdge(nsname, subname, 'SUBNAME');
    subname.setProperty('rank', rank++);

    p2 = subname.out('NEXT').next();
    if (p != it) {
        p.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
        g.idx('delete').put('node', 'delete', p);
    }
    
    g.addEdge(nsname, p2, 'NEXT');
    p = p2;
    subname.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
}

";
            unset($actions['makeNamespace']);
        }    
        
        if (isset($actions['createSequenceForDefaultWithoutSemicolon'])) {
            $sequence = new Sequence(Token::$client);
            $fullcode = $sequence->fullcode();
            $qactions[] = "

/* createSequenceForDefaultWithoutSemicolon */ 
x = g.addVertex(null, [code:'Block with Sequence For Default Without Semicolon', fullcode:'Block with Sequence For Default Without Semicolon', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');   

fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').next();
b = a.out('NEXT').next();
rank = 0;

if (a.out('ELEMENT').count() > 0) {
    a.out('ELEMENT').each{
        it.setProperty('rank', it.rank); // this is in case b is also a sequence. May be a is also a sequence....
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
        rank++;
    }
    
    g.idx('delete').put('node', 'delete', a);
} else {
    rank = 1;
    a.setProperty('rank', 0);
    g.addEdge(x, a, 'ELEMENT');
}

if (b.out('ELEMENT').count() > 0) {
    b.out('ELEMENT').each{
        it.setProperty('rank', it.rank + rank); 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    }
    
    g.idx('delete').put('node', 'delete', b);
} else {
    b.setProperty('rank', 1);
    g.addEdge(x, b, 'ELEMENT');
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, b.out('NEXT').next(), 'NEXT');

a.setProperty('rank', 0);
b.setProperty('rank', 1);

a.bothE('NEXT').each{ g.removeEdge(it) ; }
b.outE('NEXT').each{ g.removeEdge(it) ; }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

";
            unset($actions['createSequenceForDefaultWithoutSemicolon']);
        }        
        
if (isset($actions['to_void'])) {
            $qactions[] = "
/* to_void */

semicolon = it.out('NEXT').out('NEXT').next();
semicolon.setProperty('code', 'Void');
semicolon.setProperty('token', 'T_VOID');
semicolon.setProperty('atom', 'Void');
semicolon.setProperty('fullcode', ' ');
semicolon.setProperty('modifiedBy', 'to_void');


";
            unset($actions['to_void']);
        } 

if (isset($actions['insertVoid'])) {
    $out = str_repeat(".out('NEXT')", $actions['insertVoid']);
    
    $qactions[] = "
/* insert_void */

x = g.addVertex(null, [code:'void', fullcode:' ', atom:'Void', token:'T_VOID', virtual:true, line:it.line, line:it.line]);

e = it{$out}.next();
f = e.out('NEXT').next();

g.removeEdge(e.outE('NEXT').next());
g.addEdge(e, x, 'NEXT');
g.addEdge(x, f, 'NEXT');

";
            unset($actions['insertVoid']);
        } 
        
if (isset($actions['Phpcodemiddle'])) {
            $qactions[] = "
/* Phpcodemiddle */
a = it.in('NEXT').next();
c = it.out('NEXT').next();
d = c.out('NEXT').next();
e = d.out('NEXT').next();

it.setProperty('code', 'Phpcodemiddle');

g.addEdge(a, c, 'NEXT');
g.addEdge(c, e, 'NEXT');

d.bothE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

g.idx('delete').put('node', 'delete', d);
g.idx('delete').put('node', 'delete', it);

";
            unset($actions['Phpcodemiddle']);
        }                

        if (isset($actions['insertConcat3'])) {
            $qactions[] = "
/* insertConcat 3 */
x = it;
b = it.out('NEXT').next();

g.addEdge(x, b, 'CONCAT');
g.addEdge(x, b.out('NEXT').next(), 'NEXT');

b.bothE('NEXT').each{ g.removeEdge(it); }
    

";
            unset($actions['insertConcat3']);
        }           

        if (isset($actions['insertConcat4'])) {
            $qactions[] = "
/* insertConcat 4 (Scalar, Concat) */
s = it;
c = it.out('NEXT').next();

g.addEdge(c, s, 'CONCAT');
g.addEdge(s.in('NEXT').next(), c, 'NEXT');

s.bothE('NEXT').each{ g.removeEdge(it); }
    

";
            unset($actions['insertConcat4']);
        }           

        if (isset($actions['to_block'])) {
            $qactions[] = " 
/* to_block */ 

it.setProperty('block', 'true');

next = it.out('NEXT').next();

if (next.atom == 'Sequence') {
    init = it;
    next.out('ELEMENT').each{
        it.inE('ELEMENT').each{
            g.removeEdge(it);
        }
        g.addEdge(init, it, 'ELEMENT');
    }

    end = next.out('NEXT').next();
    g.addEdge(it, end.out('NEXT').next(), 'NEXT');
    end.bothE('NEXT').each{ g.removeEdge(it); }
    next.inE('NEXT').each{ g.removeEdge(it); }

    g.removeVertex(next);
    g.removeVertex(end);
} else {
    g.addEdge(it, next, 'ELEMENT');
    next.setProperty('rank', 0);
    g.addEdge(it, next.out('NEXT').out('NEXT').next(), 'NEXT');

    next.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
    next.out('NEXT').each{ g.removeVertex(it); }
    next.bothE('NEXT').each{ g.removeEdge(it); }
}

";
            unset($actions['to_block']);
        }           

        if (isset($actions['to_block_for']) && $actions['to_block_for']) {
            $sequence = new Block(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = " 
/* to_block_for */ 

x = g.addVertex(null, [code:'Block with For', fullcode:'Block with For', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, block:'true' ]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');   

fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}
            ";
            unset($actions['to_block_for']);
        }

        if (isset($actions['addToSequence'])) {
            $qactions[] = "
/* add to Sequence */
next = it.out('NEXT').next();

next.setProperty('rank', it.out('ELEMENT').count());
g.addEdge(it, next, 'ELEMENT');

nextnext = next.out('NEXT').next();
if (nextnext.token == 'T_SEMICOLON' && nextnext.atom != 'Sequence') {
    g.addEdge(it, nextnext.out('NEXT').next(), 'NEXT');
    
    nextnext.outE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(nextnext);
} else {
    g.addEdge(it, nextnext, 'NEXT');
}

next.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['addToSequence']);
        }

        if (isset($actions['checkForNext'])) {
            $qactions[] = "
/* Check for Next */

// lone instruction BEFORE
while (it.in('NEXT').filter{ it.getProperty('atom') in ['RawString', 'Void', 'Ifthen', 'Function', 'For', 'Foreach', 'Try', 
                                                        'Ternary', 'While', 'Assignation', 'Switch', 'Use', 'Label', 'Array', 
                                                        'Postplusplus', 'Preplusplus', 'Return', 'Class', 'Phpcode' ] && 
                                      it.getProperty('token') != 'T_ELSEIF'}.any() && 
    it.in('NEXT').in('NEXT').filter{ !(it.getProperty('token') in ['T_ECHO', 'T_PRINT', 'T_AND_EQUAL', 'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL', 
                                                    'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 
                                                    'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
                                                    'T_INSTANCEOF', 'T_INSTEADOF', 'T_QUESTION', 'T_DOT', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS', 
                                                    'T_ELSE']) || (it.getProperty('atom') != null && it.atom != 'Parenthesis')}.any() && 
    !it.in('NEXT').in('NEXT').filter{ it.token == 'T_COLON' && it.association == 'Ternary' }.any() 
                                                    ) {

    sequence = it;
    previous = it.in('NEXT').next();

    sequence.out('ELEMENT').each{ 
        it.setProperty('rank', it.rank + 1);
    }
    g.addEdge(sequence, previous, 'ELEMENT');
    previous.setProperty('rank', 0);
    
    previous.in('NEXT').each{ g.addEdge(it, sequence, 'NEXT')};
    previous.bothE('NEXT').each{ g.removeEdge(it); }

//    previous.setProperty('checkForNext', 'Previous');
}

// Special case for Block (Sequence + block)
while ( it.in('NEXT').filter{ it.atom == 'Sequence' && it.block == 'true' }.any() &&
    !it.in('NEXT').in('NEXT').filter{it.token in ['T_IF']}.any() &&
    !it.in('NEXT').in('NEXT').filter{!(it.token in [ 'T_USE', 'T_VOID'])}.any()) { //'T_OPEN_PARENTHESIS',
    sequence = it;
    previous = it.in('NEXT').next();
    
    sequence.out('ELEMENT').each{ 
        it.setProperty('rank', it.rank + 1);
    }
    g.addEdge(sequence, previous, 'ELEMENT');
    previous.setProperty('rank', 0);
    
    previous.in('NEXT').each{ g.addEdge(it, sequence, 'NEXT')};
    previous.bothE('NEXT').each{ g.removeEdge(it); }
//    previous.setProperty('checkForNext', 'Previous Block ' + it.in('NEXT').in('NEXT').next().token + ' / ' + it.in('NEXT').in('NEXT').filter{!(it.token in ['T_OPEN_PARENTHESIS', 'T_VOID', 'T_USE', 'T_IF'])}.count() );
}

// processing a sequence (Only the next sequence)
while (it.out('NEXT').has('atom', 'Sequence').any()) {
    sequence = it;
    c = sequence.out('ELEMENT').count();
    suivant = it.out('NEXT').next();
    
    suivant.out('ELEMENT').each{
        g.removeEdge(it.inE('ELEMENT').next());
        
        g.addEdge(sequence, it, 'ELEMENT');
//        it.setProperty('checkForNext', 'Sequence');

        it.setProperty('rank', c + it.rank);
    }
    
    g.addEdge(sequence, suivant.out('NEXT').next(), 'NEXT');

    suivant.bothE('NEXT').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', suivant);
//    suivant.setProperty('checkForNext', 'Next sequence');
}

// lone instruction AFTER
while (it.out('NEXT').filter{ it.atom in ['RawString', 'For', 'Phpcode', 'Function', 'Ifthen', 'Switch', 'Foreach', 
                                       'Dowhile', 'Try', 'Class', 'Interface', 'Trait', 'While', 'Break', 'Assignation', 'Halt',
                                       'Staticmethodcall', 'Namespace', 'Label', 'Postplusplus', 'Preplusplus', 'Include', 'Functioncall',
                                       'Methodcall', 'Variable', 'Label', 'Goto', 'Static', 'New', 'Void', 'Identifier' ] && 
                                       it.token != 'T_ELSEIF' }.any() &&
    it.out('NEXT').out('NEXT').filter{!(it.token in ['T_CATCH', 'T_FINALLY', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' ,
                                                     'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                                     'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                                     'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR', 'T_AS',
                                                     'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_INSTANCEOF', 'T_QUESTION', 'T_COLON'])}.
                               filter{it.atom != null || !(it.token in ['T_ELSEIF', 'T_OPEN_CURLY', 'T_AND_EQUAL',
                                                     'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL',
                                                     'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 
                                                     'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL',
                                                     'T_SR_EQUAL'])}.any()) {
    sequence = it;
    next = it.out('NEXT').next();
    
    next.setProperty('rank', sequence.out('ELEMENT').count());
    g.addEdge(sequence, next, 'ELEMENT');
    
    g.addEdge(sequence, next.out('NEXT').next(), 'NEXT');
    next.bothE('NEXT').each{ g.removeEdge(it); }

//    next.setProperty('checkForNext', 'Next');
    
    if (next.both('NEXT').count() == 0) {
        next.inE('INDEXED').each{ g.removeEdge(it); }
    }
} 

while (it.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).any()) {
    semicolon = it.out('NEXT').next();
    
    g.addEdge(it, semicolon.out('NEXT').next(), 'NEXT');
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', semicolon);
}

// cleaning INDEXED links when there are no more NEXT
if (it.both('NEXT').count() == 0) {
    it.inE('INDEXED').each{ g.removeEdge(it); }
}

";
            unset($actions['checkForNext']);
        }
        
        if (isset($actions['insert_global_ns'])) {
            $qactions[] = "
/* insert global namespace */
x = g.addVertex(null, [code:'Global', atom:'Identifier', token:'T_STRING', virtual:true, line:it.line, fullcode:'Global']);

g.addEdge(x, it.out('NEXT').next(), 'NEXT');
it.outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, x, 'NEXT');

";
            unset($actions['insert_global_ns']);
        }           
        
        if (isset($actions['to_specialmethodcall'])) {
            $qactions[] = "
/* to_functioncall */

funcall = it.out('NEXT').out('NEXT').next();
funcall.setProperty('block', true);

g.addEdge(it, it.in('NEXT').next(), 'CLASS');
g.addEdge(it, funcall, 'METHOD');
g.addEdge(funcall, funcall.out('NEXT').out('NEXT').out('NEXT').next() , 'ARGUMENTS');

prec = it.in('NEXT').next();
prec.inE('INDEXED').each{ g.removeEdge(it); }
g.addEdge(prec.in('NEXT').next(), it, 'NEXT');
prec.bothE('NEXT').each{ g.removeEdge(it); }

suivant = funcall.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();
x = it.out('NEXT').next();
x.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(x);

x = funcall.out('ARGUMENTS').out('NEXT').next();
x.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(x);

x = funcall.out('NEXT').out('NEXT').next();
x.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(x);

x = funcall.out('NEXT').next();
x.inE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(x);

g.addEdge(it, suivant, 'NEXT');

";
            unset($actions['to_specialmethodcall']);
        } 
        
        if (isset($actions['insert_ns'])) {
            $qactions[] = "
/* insert namespace */

it.setProperty('no_block', 'true');

g.addEdge(it, it.out('NEXT').next(), 'NAMESPACE');
g.idx('delete').put('node', 'delete', it.out('NEXT').out('NEXT').next());
g.addEdge(it, it.out('NEXT').out('NEXT').out('NEXT').next(), 'BLOCK');

end = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

it.out('NEXT').out('NEXT').out('NEXT').bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }
it.out('NEXT').bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }

g.addEdge(it, end, 'NEXT');

";
            unset($actions['insert_ns']);
        }      
        
        if (isset($actions['insert_ns_void'])) {
            $qactions[] = "
/* insert void for namespace */

it.setProperty('no_block', 'true');

g.addEdge(it, a1, 'NAMESPACE');

tvoid = g.addVertex(null, [code:'void', fullcode:' ', atom:'Void', token:'T_VOID', virtual:true, line:it.line, line:it.line]);
g.addEdge(it, tvoid, 'BLOCK');

a1.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }
a2.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }
g.idx('delete').put('node', 'delete', a2);

g.addEdge(it, a3, 'NEXT');

";
            unset($actions['insert_ns_void']);
        }           
        if (isset($actions['sign'])) {
            $qactions[] = "
/* Sign the integer */
if (it.code == '-') { 
    it.setProperty('code', '-' + it.out('NEXT').next().code);
//    it.setProperty('code', - Integer.parseInt(it.out('NEXT').next().code));
} else {
    it.setProperty('code', it.out('NEXT').next().code);
}

nextnext = it.out('NEXT').out('NEXT').next();
g.idx('delete').put('node', 'delete', it.out('NEXT').next());
it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, nextnext, 'NEXT');

";
            unset($actions['sign']);
        }           

        if (isset($actions['to_catch'])) {
            $fullcode = $this->fullcode();

            $qactions[] = "
/* to_catch or to_finally */
thecatch = it.out('NEXT').next();
next = thecatch.out('NEXT').next();

thecatch.setProperty('rank', it.out('CATCH').count());
g.addEdge(it, thecatch, 'CATCH');
g.addEdge(it, next, 'NEXT');
thecatch.bothE('NEXT').each{ g.removeEdge(it); }

fullcode = it;
$fullcode

";
            unset($actions['to_catch']);
        }           

        if (isset($actions['to_typehint'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* to type hint */
x = g.addVertex(null, [code:'Typehint', atom:'Typehint', token:'T_TYPEHINT', virtual:true, line:it.line]);

a = it.out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').out('NEXT').next(), 'NEXT');

g.addEdge(x, a, 'CLASS');
a.has('token', 'T_ARRAY').each{ 
    it.setProperty('atom', 'Identifier'); 
    it.setProperty('fullcode', it.code); 
}
g.addEdge(x, a.out('NEXT').next(), 'VARIABLE');

a.out('NEXT').bothE('NEXT').each{ g.removeEdge(it);}    
a.bothE('NEXT').each{ g.removeEdge(it);}    

/* Remove children's index */  
x.outE.hasNot('label', 'NEXT').inV.each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

/* indexing */  
    g.idx('atoms').put('atom', 'Typehint', x);

fullcode = x;
$fullcode
";
            $this->set_atom = true;
            unset($actions['to_typehint']);
        }              
        
        if (isset($actions['fullcode'])) {
            $this->set_atom = true;
            unset($actions['fullcode']);
        }
        
        if (isset($actions['insertEdge'])) {
            foreach($actions['insertEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                
                $fullcode = $this->fullcode();
                
                $qactions[] = "
/* insertEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom', token:'T_VOID', virtual:true, line:it.line, line:it.line]);

f = it.out('NEXT').out('NEXT').next();

g.addEdge(it, x, 'NEXT');
g.addEdge(x, f, 'NEXT');
g.addEdge(x, it.out('NEXT').next(), '$link');
g.removeEdge(it.outE('NEXT').next());
g.removeEdge(x.out('$link').outE('NEXT').next());

x.out('$link').inE('INDEXED').each{    
    g.removeEdge(it);
} 

fullcode = x;
$fullcode
";
            } else {
                print "No support for insertEdge with destination less than 0\n";
            }
            unset($actions['insertEdge']);
            }
        }

        if (isset($actions['addEdge'])) {
            foreach($actions['addEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $qactions[] = "
/* addEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);

f = it.out('NEXT').next();

g.removeEdge(it.outE('NEXT').next());
g.addEdge(it, x, 'NEXT');
g.addEdge(x,  f, 'NEXT');

";
            } elseif ($destination == -1) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $qactions[] = "
/* addEdge in */
x = g.addVertex(null, [code:'void', token:'T_VOID', atom:'$atom', virtual:true, line:it.line, fullcode:' ']);

f = it.in('NEXT').next();

g.removeEdge(it.inE('NEXT').next());
g.addEdge(x, it, 'NEXT');
g.addEdge(f, x, 'NEXT');

";
            } elseif ($destination > 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $next = str_repeat(".out('NEXT')", $destination - 1);
                
                $qactions[] = "
/* addEdge out $destination */ 
x = g.addVertex(null, [code:'void', token:'T_VOID', atom:'$atom', virtual:true, line:it.line, fullcode:' ']);

a = it$next.next();
b = a.out('NEXT').next();

g.removeEdge(a.outE('NEXT').next());
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

";
            } else {
                print "Only support for addEdge with destination -1 or 0\n";
            }
            unset($actions['addEdge']);
            }
        }
        
        if (isset($actions['mergeNext']) && $actions['mergeNext']) {
            foreach($actions['mergeNext'] as $atom => $link) {
                $qactions[] = " 
/* mergeNext */ 

c = it.out('$link').has('rank', 0).has('atom', '$atom').count();
d = it.out('$link').has('rank', 1).has('atom', '$atom').count();

if (c == 1) { // there is a list of argument in rank 0
    if (d == 1) { // 0 and 1 are multiple list
        sub = it.out('$link').has('rank', 0).next();
        n = it.out('$link').has('rank', 0).out('$link').count() ;
        
        it.out('$link').has('rank', 1).out('$link').each{
            g.addEdge(sub, it, '$link');
            it.setProperty('rank', it.getProperty('rank') + n);
        }

        it.out('$link').has('rank', 1).outE('$link').each{
            g.removeEdge(it);
        }

        g.addEdge(it.in('NEXT').next(), sub, 'NEXT');
        g.addEdge(sub, it.out('NEXT').next(), 'NEXT');

        g.idx('delete').put('node', 'delete', it);
        g.idx('delete').put('node', 'delete', it.out('$link').has('rank', 1).next());
        it.bothE('NEXT').each{ g.removeEdge(it); }
        it.outE('$link').each{ g.removeEdge(it); }

        clean = sub;
    } else { // 0 is multiple, 1 is single
        sub = it.out('$link').has('rank', 0).next();
        n = sub.out('$link').count();

        g.addEdge(sub, it.out('$link').has('rank', 1).next(), '$link');
        it.out('$link').has('rank', 1).next().setProperty('rankedby', 'zero_is_multiple');
        it.out('$link').has('rank', 1).next().setProperty('rank', n);
        
        g.addEdge(it.in('NEXT').next(), sub, 'NEXT');
        g.addEdge(sub, it.out('NEXT').next(), 'NEXT');
        
        g.idx('delete').put('node', 'delete', it);
        it.bothE('NEXT').each{ g.removeEdge(it); }
        it.outE('$link').each{ g.removeEdge(it); }

        clean = sub;
    }
} else { // rank 0 is single
    if (d == 1) {
        it.out('$link').has('rank', 0).next().setProperty('rankedby', 'one_is_multiple');
        sub = it.out('$link').has('rank', 1).next();
        sub.out('$link').each{ it.setProperty( 'rank', it.rank + 1); };

        g.addEdge(sub, it.out('$link').has('rank', 0).next(), '$link');
        
        g.addEdge(it.in('NEXT').next(), sub, 'NEXT');
        g.addEdge(sub, it.out('NEXT').next(), 'NEXT');
        
        g.idx('delete').put('node', 'delete', it);
        it.bothE('NEXT').each{ g.removeEdge(it); }
        it.outE('$link').each{ g.removeEdge(it); }
        clean = sub;

    } else {
        // rank 1 and 0 are both singles : Nothing to do.
        it.out('$link').each{ it.setProperty('rankedby', 'both_are_single')};
        clean = it;
    }
}

// automated clean Index
clean.out('$link').inE('INDEXED').each{
    g.removeEdge(it);
}

fullcode = clean;
";
            }
            unset($actions['mergeNext']);
        }
        
        if (isset($actions['createSequenceWithNext']) && $actions['createSequenceWithNext']) {
                $qactions[] = " 
/* createSequenceWithNext */ 

x = g.addVertex(null, [code:'Sequence With Next', fullcode:'Sequence With Next', atom:'Sequence', virtual:true, line:it.line]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');   

i = it.out('NEXT').next();

g.addEdge(it, x, 'NEXT');
g.addEdge(x, i, 'ELEMENT');
i.setProperty('rank', 0);
g.addEdge(x, i.out('NEXT').next(), 'NEXT');

i.bothE('NEXT').each{ g.removeEdge(it) ; }

x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it) ; }
    
    g.removeVertex(it);
}
            ";
            unset($actions['createSequenceWithNext']);
        }

        if (isset($actions['to_block_else']) && $actions['to_block_else']) {
            $sequence = new Sequence(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = " 
/* to_block_else */

x = g.addVertex(null, [code:'Block with else', fullcode:' /**/ ', token:'T_SEMICOLON', atom:'Sequence', block:'true', virtual:true, line:it.line]);

a = it.out('NEXT').next();
if (a.token == 'T_COLON') {
    a = a.out('NEXT').next();
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    semicolon = it;
    g.addEdge(x, it.out('NEXT').next(), 'NEXT');
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

            ";
            unset($actions['to_block_else']);
        }
        
        if (isset($actions['to_block_foreach']) && $actions['to_block_foreach']) {
            $sequence = new Block(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = " 
/* to_block_foreach */  

x = g.addVertex(null, [code:'Block with Foreach', fullcode:'Block with Foreach', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, line:it.line, block:'true']);
g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');   

fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}
            ";
            unset($actions['to_block_foreach']);
        }
        
        if (isset($actions['to_block_ifelseif']) && $actions['to_block_ifelseif']) {
            $block = new Block(Token::$client);
            $fullcode = $block->fullcode();
            
            $offset = str_repeat(".out('NEXT')", $actions['to_block_ifelseif']);
            $qactions[] = " 
/* to_block_ifelseif ({$actions['to_block_ifelseif']})*/ 

x = g.addVertex(null, [code:'Block with if/elseif', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, block:'true' ]);

fullcode = x;
$fullcode

a = it$offset.next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', semicolon);   
}

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}
            ";
            unset($actions['to_block_ifelseif']);
        }
        
        if (isset($actions['to_block_ifelseif_instruction']) && $actions['to_block_ifelseif_instruction']) {
                $qactions[] = " 
/* to_block_ifelseif_instruction */ 

x = g.addVertex(null, [code:'Block with control if elseif', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, fullcode:'Block with control if elseif', block:'true' ]);
a = it.out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['to_block_ifelseif_instruction']);
        }
                
        if (isset($actions['arguments2extends']) && $actions['arguments2extends']) {
                $qactions[] = " 
/* arguments2extends */ 
            ";
            unset($actions['arguments2extends']);
        }

        if (isset($actions['createBlockWithSequence']) && $actions['createBlockWithSequence']) {
                $qactions[] = " 
/* createBlockWithSequence */ 
x = g.addVertex(null, [code:'Block with Next', fullcode:'Block with Next', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it, 'ELEMENT');
it.setProperty('rank', 0);
g.addEdge(x, it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it) ; }
            ";
            unset($actions['createBlockWithSequence']);
        }
        
        if (isset($actions['createBlockWithSequenceForCase']) && $actions['createBlockWithSequenceForCase']) {
            $sequence = new Sequence(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = " 
/* createBlockWithSequenceForCase */ 
x = g.addVertex(null, [code:'Block with Sequence For Case', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line]);
fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').out('NEXT').next();

a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

            ";
            unset($actions['createBlockWithSequenceForCase']);
        }

        if (isset($actions['createBlockWithSequenceForDefault']) && $actions['createBlockWithSequenceForDefault']) {
            $sequence = new Sequence(Token::$client);
            $fullcode = $sequence->fullcode();

            $qactions[] = " 
/* createBlockWithSequenceForDefault */ 
x = g.addVertex(null, [code:'Block with Sequence For Default', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
fullcode = x;
$fullcode

a = it.out('NEXT').out('NEXT').next();

a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            unset($actions['createBlockWithSequenceForDefault']);
        }

        if (isset($actions['createVoidForCase']) && $actions['createVoidForCase']) {
            $qactions[] = " 
/* createVoidForCase */ 
x = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
a = it.out('NEXT').out('NEXT').next();
b = a.out('NEXT').next();

a.outE('NEXT').each{ g.removeEdge(it) ; }
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            unset($actions['createVoidForCase']);
        }

        if (isset($actions['createVoidForDefault']) && $actions['createVoidForDefault']) {
            $qactions[] = " 
/* createVoidForDefault */ 
x = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
a = it.out('NEXT').next();
b = a.out('NEXT').next();

a.outE('NEXT').each{ g.removeEdge(it) ; }
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            unset($actions['createVoidForDefault']);
        }

        if (isset($actions['makeMethodCall'])) {
            $methodcall = new Methodcall(Token::$client);
            $fullcode = $methodcall->fullcode();

            $qactions[] = " 
/* makeMethodCall */ 

p = it;

while(p.token == 'T_OBJECT_OPERATOR' &&
      p.out('NEXT').has('atom', 'Functioncall').any() ) {
    g.addEdge(p, p.in('NEXT').next(), 'OBJECT');
    g.addEdge(p, p.out('NEXT').next(), 'METHOD');

    a = p.in('NEXT').in('NEXT').next();
    c = p.out('NEXT').out('NEXT').next();

    p.in('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
    p.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }

    g.addEdge(a, p, 'NEXT');
    g.addEdge(p, c, 'NEXT');
    
    p.inE('INDEXED').each{ g.removeEdge(it); }
    p.setProperty('atom', 'Methodcall');
    
    fullcode = p;
    $fullcode;
    
    a = p;
    p = c;
}

p = a;

            ";
            unset($actions['makeMethodCall']);
        }
        if (isset($actions['caseDefaultSequence'])) {
            $qactions[] = <<<GREMLIN
 /* caseDefaultSequence */   

    if (it.both('NEXT').has('atom', 'SequenceCaseDefault').count() == 2) {
        cds = it.in('NEXT').next();

        g.addEdge(cds, it, 'ELEMENT');
        c = cds.out('ELEMENT').count();
        it.setProperty('rank', c - 1);
        
        cds2 = it.out('NEXT').next();
        cds2.out('ELEMENT').each{
            it.inE('ELEMENT').each {
                g.removeEdge(it);
            }

            g.addEdge(cds, it, 'ELEMENT');
            it.setProperty('rank', c + it.rank);
        }
        
        g.addEdge(cds, cds2.out('NEXT').next(), 'NEXT');
        cds2.bothE('NEXT').each { g.removeEdge(it); }
        it.inE('NEXT').each { g.removeEdge(it); }
        
        g.idx('delete').put('node', 'delete', cds2);   
    } else if (it.out('NEXT').has('atom', 'SequenceCaseDefault').any()) {
            cds = it.out('NEXT').next();

            it.setProperty('rank', 0);
            cds.out('ELEMENT').each{ it.setProperty('rank', it.rank + 1); }
            
            g.addEdge(it.in('NEXT').next(), cds, 'NEXT');
            g.addEdge(cds, it, 'ELEMENT');
            it.bothE('NEXT').each{ g.removeEdge(it); }
    } else if (it.in('NEXT').has('atom', 'SequenceCaseDefault').any()) {
            cds = it.in('NEXT').next();

            it.setProperty('rank', cds.out('ELEMENT').count());
            
            g.addEdge(cds, it.out('NEXT').next(), 'NEXT');
            g.addEdge(cds, it, 'ELEMENT');
            it.bothE('NEXT').each{ g.removeEdge(it); }
    } else {
        // no caseDefaultSequence anywhere
        cds = g.addVertex(null, [code:'Sequence Case Default', atom:'SequenceCaseDefault', token:'T_SEQUENCE_CASEDEFAULT', virtual:true, line:it.line, fullcode:'{ /**/ }']);

        it.setProperty('rank', 0);

        g.addEdge(it.in('NEXT').next(), cds, 'NEXT');
        g.addEdge(cds, it.out('NEXT').next(), 'NEXT');
        g.addEdge(cds, it, 'ELEMENT');
    
        it.bothE('NEXT').each{ g.removeEdge(it); }
    }

    
GREMLIN;
            unset($actions['caseDefaultSequence']);
        }

        if (isset($actions['mergePrev2']) && $actions['mergePrev2']) {
            foreach($actions['mergePrev2'] as $atom => $link) {
                $qactions[] = " 
/* mergePrev */ 
x = it;
it.as('origin').out('ELEMENT').has('atom','Sequence').each{
    it.inE('ELEMENT').each{ g.removeEdge(it);}
  
    it.out('ELEMENT').each{ 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    };

    g.idx('delete').put('node', 'delete', it);   
}
";
            }
            unset($actions['mergePrev2']);
        }

        if (isset($actions['to_variable'])) {
            $qactions[] = " 
/* to variable */ 
variable = it.out('NEXT').next();
variable.setProperty('delimiter', it.code);
variable.setProperty('enclosing', it.token);

g.idx('delete').put('node', 'delete', it);   
g.addEdge(it.in('NEXT').next(), variable, 'NEXT');
it.bothE('NEXT').each{ g.removeEdge(it); }

g.idx('delete').put('node', 'delete', variable.out('NEXT').next());   
close_curly = variable.out('NEXT').next();
g.addEdge(variable, variable.out('NEXT').out('NEXT').next(), 'NEXT');
close_curly.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['to_variable']);
        }
        
        if (isset($actions['makeForeachSequence'])) {
            $qactions[] = " 
/* make Foreach Sequence */ 
block = g.addVertex(null, [code:'Block with Foreach', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, modifiedBy:'_Foreach', fullcode:'{ /**/ } ']);
element1 = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();
element2 = element1.out('NEXT').next();

g.addEdge(block, element1, 'ELEMENT');
element1.setProperty('rank', 0);
g.addEdge(block, element2, 'ELEMENT');
element2.setProperty('rank', 1);

g.addEdge(element1.in('NEXT').next(), block, 'NEXT');
g.addEdge(block, element2.out('NEXT').next(), 'NEXT');
element1.bothE('NEXT').each{ g.removeEdge(it); }
element2.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['makeForeachSequence']);
        }

        if (isset($actions['while_to_block'])) {
            $qactions[] = " 
/* while_to_block */  

x = g.addVertex(null, [code:'Block with While', token:'T_SEMICOLON', atom:'Sequence', virtual:true, block:'true', line:it.line, modifiedBy:'_While', fullcode:' /**/  ']);
a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

";
            unset($actions['while_to_block']);
        }        

        if (isset($actions['makeSequence'])) {
            $it = $actions['makeSequence'];
            
            $qactions[] = "
/* makeSequence */

list_before = ['T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER',
        'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE',
        'T_AND_EQUAL', 'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
        'T_COMMA', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
        'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 
        'T_ECHO', 'T_PRINT',
        'T_EXTENDS', 'T_IMPLEMENTS', 'T_USE',
        'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
        'T_PLUS', 'T_MINUS',
        'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_POW',
        'T_COLON', 'T_NEW', 'T_DOT', 
        'T_SR','T_SL', 'T_CURLY_OPEN',
        'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_VAR',
        'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
        'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
        'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR',
        'T_NAMESPACE', 'T_DOUBLE_ARROW',
        'T_THROW', 'T_CLONE', 'T_RETURN', 
        'T_DOLLAR', 'T_DOLLAR_OPEN_CURLY_BRACES',
        'T_ABSTRACT', 'T_FINAL', 'T_STATIC', 'T_CONST', 
        'T_AT', 'T_CASE', 
        'T_ARRAY_CAST','T_BOOL_CAST', 'T_DOUBLE_CAST','T_INT_CAST','T_OBJECT_CAST','T_STRING_CAST','T_UNSET_CAST',
        'T_DO', 'T_TRY',
        'T_STRING', 'T_INSTEADOF', 'T_INSTANCEOF', 'T_BANG',
        'T_ELSE', 'T_INC', 'T_DEC', 'T_IF', 'T_ELSEIF',
        'T_CONST', 'T_FUNCTION', 'T_FINALLY'
        ];

list_after = [
        'T_COMMA', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
        'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 
        'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
        'T_PLUS', 'T_MINUS',
        'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_POW',
        'T_COLON', 'T_NEW', 'T_DOT', 
        'T_SR','T_SL', 'T_CURLY_OPEN',
        'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
        'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
        'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR',

        'T_ELSE', 'T_ELSEIF', 
        'T_CATCH'];

list_after_token = [
        'T_OBJECT_OPERATOR', 'T_INC', 'T_DEC', 
        'T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER',
        'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
        'T_AND_EQUAL', 'T_CONCAT_EQUAL', 
        'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_POW',
        'T_PLUS', 'T_MINUS',
        'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
        'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
        'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR',
        'T_AS', 'T_DOT', 'T_INSTANCEOF', 'T_QUESTION'
        ];

if (     $it.token != 'T_ELSEIF'
    &&   $it.in('NEXT').any()
    &&   $it.out('NEXT').any()
    &&  ($it.root != 'true' || $it.out('NEXT').next().atom == 'RawString' )
    &&  ($it.in('NEXT').next().atom != null || !($it.in('NEXT').next().token in list_before))
    &&  ($it.in('NEXT').next().atom != null || !($it.out('NEXT').next().token in list_after) )
    &&   $it.in_quote != \"'true'\"
    &&   $it.in_for != \"'true'\"
    && !($it.in('NEXT').next().atom in ['Class', 'Identifier']) 
    && !($it.out('NEXT').next().token in list_after_token)
    && !($it.in('NEXT').next().token in ['T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS', 'T_STRING', 'T_NS_SEPARATOR', 'T_CALLABLE'])
    && !($it.in('NEXT').has('token', 'T_OPEN_CURLY').any() && $it.in('NEXT').in('NEXT').filter{ it.token in ['T_VARIABLE', 'T_OPEN_CURLY', 'T_CLOSE_CURLY', 'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOLLAR']}.any()) /* \$x{\$b - 2} */
    ) {

//    $it.setProperty('makeSequence32', $it.in('NEXT') .next().token) ;
//    $it.setProperty('makeSequence4',  $it.out('NEXT').next().token);

    if ( $it.both('NEXT').has('atom', 'Sequence').count() == 2 &&
        ($it.in('NEXT').next().block != 'true')) {
        count = $it.in('NEXT').out('ELEMENT').count();
        sequence = $it.in('NEXT').next();
    
        $it.setProperty('rank', count);
        count++;
//        $it.setProperty('makeSequence', 'both');
        g.addEdge(sequence, $it, 'ELEMENT');

        $it.out('NEXT').out('ELEMENT').each{ 
            it.setProperty('rank', it.rank + count);
        
            it.inE('ELEMENT').each{ g.removeEdge(it); }
            g.addEdge(sequence, it, 'ELEMENT');
        }
    
        g.addEdge(sequence, $it.out('NEXT').out('NEXT').next(), 'NEXT');

        sequence2 = $it.out('NEXT').next();
        $it.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
        g.idx('delete').put('node', 'delete', sequence2);
        $it.bothE('NEXT').each{ g.removeEdge(it); }
    } else if ($it.in('NEXT').has('atom', 'Sequence').any() &&
              ($it.in('NEXT').next().block != 'true')) {
        sequence = $it.in('NEXT').next();
        $it.setProperty('rank', $it.in('NEXT').out('ELEMENT').count());
//        $it.setProperty('makeSequence', 'in');

        g.addEdge(sequence, $it.out('NEXT').next(), 'NEXT');
        g.addEdge($it.in('NEXT').next(), $it, 'ELEMENT');

        $it.bothE('NEXT').each{ g.removeEdge(it); }
    } else if ($it.out('NEXT').has('atom', 'Sequence').any()) {
        sequence = $it.out('NEXT').next();
        $it.setProperty('rank', 0);
//        $it.setProperty('makeSequence', 'next');
        sequence.out('ELEMENT').each{ it.setProperty('rank', it.rank + 1);}

        g.addEdge($it.out('NEXT').next(), $it, 'ELEMENT');

        g.addEdge($it.in('NEXT').next(), $it.out('NEXT').next(), 'NEXT');

        $it.bothE('NEXT').each{ g.removeEdge(it); }
    } else {
        sequence = g.addVertex(null, [code:'makeSequence ' + $it.in('NEXT').next().token, atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:$it.line, fullcode:';']);
        g.addEdge(g.idx('racines')[['token':'Sequence']].next(), sequence, 'INDEXED');   
        g.idx('atoms').put('atom', 'Sequence', sequence);   

        g.addEdge(sequence, $it, 'ELEMENT');
        $it.setProperty('rank', 0);
//        $it.setProperty('makeSequence', 'else');
        
        if ($it.root == 'true') { 
            sequence.setProperty('root', 'true'); 
            g.addEdge($it.in('FILE').next(), sequence, 'FILE');
            
            $it.setProperty('root', 'false'); 
            $it.inE('FILE').each{ g.removeEdge(it); }
        }

        g.addEdge($it.in('NEXT').next(), sequence, 'NEXT');
        g.addEdge(sequence, $it.out('NEXT').next(), 'NEXT');
    
        $it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
    }
} else {
    /*
    $it.setProperty('makeSequence1',   $it.token != 'T_ELSEIF');
    $it.setProperty('makeSequence2',  ($it.root != 'true' || $it.out('NEXT').next().atom == 'RawString' ));
    $it.setProperty('makeSequence3',  ($it.in('NEXT').next().atom != null || !($it.in('NEXT').next().getProperty('token') in list_before))) ;
    $it.setProperty('makeSequence31',  $it.in('NEXT').next().atom != null);
    $it.setProperty('makeSequence32',  $it.in('NEXT') .next().token) ;
    $it.setProperty('makeSequence41',  $it.out('NEXT').next().token);
    $it.setProperty('makeSequence4',   (!($it.out('NEXT').next().token in list_after) ));
    $it.setProperty('makeSequence5',   $it.in_quote != 'true' );
    $it.setProperty('makeSequence6',   $it.in_for != 'true' );
    $it.setProperty('makeSequence7',   !($it.in('NEXT').next().atom in ['Class', 'Identifier']) );
    $it.setProperty('makeSequence9',   !($it.out('NEXT').next().token in list_after_token));
    $it.setProperty('makeSequence10',   !($it.in('NEXT').next().token in ['T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS', 'T_STRING', 'T_NS_SEPARATOR']));
    */
}

";
            unset($actions['makeSequence']);
        }

        if (isset($actions['to_variable_dollar'])) {
            $qactions[] = " 
/* to variable */ 
variable = it.out('NEXT').out('NEXT').next();
variable.setProperty('delimiter', '\${');

g.idx('delete').put('node', 'delete', it.out('NEXT').next());   
g.idx('delete').put('node', 'delete', it);   
g.addEdge(it.in('NEXT').next(), variable, 'NEXT');
it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

g.idx('delete').put('node', 'delete', variable.out('NEXT').next());   
close_curly = variable.out('NEXT').next();
g.addEdge(variable, variable.out('NEXT').out('NEXT').next(), 'NEXT');
close_curly.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['to_variable_dollar']);
        }
                        
        if (isset($actions['mergePrev']) && $actions['mergePrev']) {
            foreach($actions['mergePrev'] as $atom => $link) {
                $qactions[] = " 
/* mergePrev */ 
x = g.addVertex(null, [code:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
y = it.in('NEXT').in('NEXT').next();
z = it.in('NEXT').next();
a = it;
b = it.out('NEXT').next();

g.addEdge(x, z, 'ELEMENT');
g.addEdge(x, a, 'ELEMENT');

z.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(y, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it); }

x.as('origin').out('ELEMENT').has('atom','Sequence').each{
    it.inE('ELEMENT').each{ g.removeEdge(it);}
  
    it.out('ELEMENT').each{ 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    };

    g.idx('delete').put('node', 'delete', it);   
}

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            }
            unset($actions['mergePrev']);
        }

        if (isset($actions['make_quoted_string'])) {
            $concatenation = new Concatenation(Token::$client);
            $fullcode = $concatenation->fullcode();

            $atom = $actions['make_quoted_string'];
            $class = "\\Tokenizer\\$atom";
            $string = new $class(Token::$client);
            $fullcode2 = $string->fullcode();
            
            $qactions[] = " 
/* make_quoted_string */ 

x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);

rank = 0;
it.out('NEXT').loop(1){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}{!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.each{
    if (it.token in ['T_CURLY_OPEN', 'T_CLOSE_CURLY']) {
        it.inE('NEXT').each{ g.removeEdge(it);}
        g.idx('delete').put('node', 'delete', it);
    } else {
        g.addEdge(x, it, 'CONCAT');
        it.setProperty('rank', rank);
        rank++;
        it.inE('NEXT').each{ g.removeEdge(it);}
        f = it;
    }
}

g.addEdge(it, x, 'CONTAIN');
g.addEdge(it, f.out('NEXT').out('NEXT').next(), 'NEXT');

g.idx('delete').put('node', 'delete', f.out('NEXT').next());
g.removeEdge(f.out('NEXT').outE('NEXT').next());

it.setProperty('atom', 'String');

fullcode = x;
$fullcode

fullcode = it;
$fullcode2 

/* Clean index */
x.out('CONCAT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

/* indexing */  g.idx('atoms').put('atom', 'String', it);

";
            unset($actions['make_quoted_string']);
        }
        
        if (isset($actions['mergeConcat'])) {
            $fullcode = $this->fullcode();

            $qactions[] = " 
/* mergeConcat */ 
x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);

z = it.in('NEXT').next();
a = it;
b = it.out('NEXT').next();
c = it.out('NEXT').out('NEXT').next();

g.addEdge(x, a, 'ELEMENT');
g.addEdge(x, b, 'ELEMENT');

b.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(z, x, 'NEXT');
g.addEdge(x, c, 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it); }

x.as('origin').out('ELEMENT').has('atom','Concatenation').each{
    it.inE('ELEMENT').each{ g.removeEdge(it);}
  
    it.out('ELEMENT').each{ 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    };

    g.idx('delete').put('node', 'delete', it);
}

fullcode = x;
$fullcode

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

            ";
            unset($actions['mergeConcat']);
        }
        
        if (isset($actions['add_to_index'])) {
            list($index, $token) = each($actions['add_to_index']);
            $qactions[] = " 
/* add to the following index */  

g.addEdge(g.idx('racines')[['token':'$token']].next(), it, 'INDEXED');   

";
            unset($actions['add_to_index']);
        }        

        if (isset($actions['while_to_empty_block'])) {
            $qactions[] = " 
/* create an empty Block in place of a semi colon, after a while statment.  */  

x = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();
x.setProperty('code', 'Empty Block with While');
x.setProperty('atom', 'Sequence');

                ";
            unset($actions['while_to_empty_block']);
        }        

        if (isset($actions['checkTypehint'])) {
            $qactions[] = " 
/* Turn a & b into a typehint  */  

it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Logical').each {
    it.setProperty('atom', 'Typehint');
    
    g.addEdge(it, it.out('LEFT').next(), 'CLASS');
    g.removeEdge(it.outE('LEFT').next());

    g.addEdge(it, it.out('RIGHT').next(), 'VARIABLE');
    g.removeEdge(it.outE('RIGHT').next());
    
    it.out('VARIABLE').next().setProperty('reference', 'true');
    
    g.idx('atoms').put('atom', 'Typehint', it);
}

                ";
            unset($actions['checkTypehint']);
        }  
        
        if (isset($actions['variable_to_functioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = " 
/* create a functioncall, and hold the variable as property.  */  

x = g.addVertex(null, [code:it.code, fullcode: it.code, atom:'Variable', token:'T_VARIABLE', virtual:true, line:it.line, modifiedBy:'FunctionCall']);
g.addEdge(it, x, 'NAME');
g.idx('atoms').put('atom', 'Variable', x);
                ";
            unset($actions['variable_to_functioncall']);
        }        

        if (isset($actions['array_to_functioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = " 
/* hold the array as property.  */  

x = g.addVertex(null, [code:it.code, fullcode: it.fullcode, atom:'Array', token:'T_OPEN_BRACKET', virtual:true, line:it.line, modifiedBy:'FunctionCallArray']);
g.addEdge(it, x, 'NAME');
g.idx('atoms').put('atom', 'Array', x);
                ";
            unset($actions['array_to_functioncall']);
        }        

        if (isset($actions['cleanIndex'])) {
            $qactions[] = " 
/* Remove children's index */  
it.out('NAME', 'PROPERTY', 'OBJECT', 'DEFINE', 'CODE', 'LEFT', 'RIGHT', 'SIGN', 'NEW', 'RETURN', 'CONSTANT', 'CLASS', 'VARIABLE',
'INDEX', 'EXTENDS', 'SUBNAME', 'POSTPLUSPLUS', 'PREPLUSPLUS', 'VALUE', 'CAST', 'SOURCE', 'USE', 'KEY', 'IMPLEMENTS', 'THEN', 'AS', 
'ELSE', 'NOT', 'CONDITION', 'CASE', 'THROW', 'METHOD', 'STATIC', 'CLONE', 'INIT', 'AT', 'ELEMENT','FINAL', 'FILE', 'NAMESPACE', 'LABEL',
'YIELD').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}
                ";
            unset($actions['cleanIndex']);
        }        

        if ($remainder = array_keys($actions)) {
            print "Warning : the following ".count($remainder)." actions were ignored : ".join(', ', $remainder)."\n";
        }

        return $qactions;
    }

    private function readConditions($cdt) {
        $qcdts = array();

        if (isset($cdt['next'])) {
            for($i = 0; $i < $cdt['next']; $i++) {
                $qcdts[] = "out('NEXT')";
            }
            $qcdts[] = "sideEffect{ a{$cdt['next']} = it;}";
            unset($cdt['next']);
        }

        if (isset($cdt['previous'])) {
            for($i = 0; $i < $cdt['previous']; $i++) {
                $qcdts[] = "in('NEXT')";
            }
            $qcdts[] = "sideEffect{ b{$cdt['previous']} = it;}";
            unset($cdt['previous']);
        }

        if (isset($cdt['property'])) {
            foreach($cdt['property'] as $property => $value) {
                if (is_array($value)) {
                    $qcdts[] = "filter{it.$property in ['".join("', '", $value)."']}";
                } else {
                    $qcdts[] = "has('$property', '$value')";
                }
            }
            unset($cdt['property']);
        }

        if (isset($cdt['check_for_string'])) {
            if (is_array($cdt['check_for_string'])) {
                $classes = "'".join("', '", $cdt['check_for_string'])."'";
            } else {
                $classes = "'".$cdt['check_for_string']."'";
            }
            $qcdts[] = "as('cfs').out('NEXT').filter{ it.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'] || it.atom in [$classes] }.loop(2){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.back('cfs')";
            unset($cdt['check_for_string']);
        }

        if (isset($cdt['code'])) {
            if (is_array($cdt['code']) && !empty($cdt['code'])) {
                $qcdts[] = "filter{it.code in ['".join("', '", $cdt['code'])."']}";
            } else {
                $qcdts[] = "has('code', '".$cdt['code']."')";
            }
            unset($cdt['code']);
        }

        if (isset($cdt['icode'])) {
            if (is_array($cdt['icode']) && !empty($cdt['icode'])) {
                $qcdts[] = "hasNot('code', null).filter{it.code.toLowerCase() in ['".join("', '", $cdt['icode'])."']}";
            } else {
                $qcdts[] = "hasNot('code', null).filter{it.code.toLowerCase() == '".$cdt['icode']."'}";
            }
            unset($cdt['icode']);
        }

        if (isset($cdt['notcode']) && is_array($cdt['notcode']) && !empty($cdt['notcode'])) {
            $qcdts[] = "filter{!(it.code in ['".join("', '", $cdt['notcode'])."'])}";
            unset($cdt['notcode']);
        }

        if (isset($cdt['token'])) {
            if ( is_array($cdt['token']) && !empty($cdt['token'])) {
                $qcdts[] = "filter{it.token in ['".join("', '", $cdt['token'])."']}";
            } else {
                $qcdts[] = "has('token', '".$cdt['token']."')";
            }
            unset($cdt['token']);
        }

        if (isset($cdt['notToken'])) {
            if ( is_array($cdt['notToken']) && !empty($cdt['notToken'])) {
                $qcdts[] = "filter{!(it.token in ['".join("', '", $cdt['notToken'])."'])}";
            } else {
                $qcdts[] = "hasNot('token', '".$cdt['notToken']."')";
            }
            unset($cdt['notToken']);
        }
        
        if (isset($cdt['atom'])) {
            if ( is_array($cdt['atom']) && !empty($cdt['atom'])) {
                $qcdts[] = "filter{it.atom in ['".join("', '", $cdt['atom'])."']}";
            } elseif ( is_string($cdt['atom']) && $cdt['atom'] == 'none') {
                $qcdts[] = "has('atom', null)";
            } elseif ( is_string($cdt['atom']) && $cdt['atom'] == 'yes') {
                $qcdts[] = "hasNot('atom', null)";
            } else {
                $qcdts[] = "has('atom', '".$cdt['atom']."')";
            }
            unset($cdt['atom']);
        }

        if (isset($cdt['notAtom'])) {
            if ( is_array($cdt['notAtom']) && !empty($cdt['notAtom'])) {
                $qcdts[] = "filter{!(it.atom in ['".join("', '", $cdt['notAtom'])."'])}";
            } else {
                $qcdts[] = "hasNot('atom', '".$cdt['notAtom']."')";
            }
            unset($cdt['notAtom']);
        }

        if (isset($cdt['in_quote'])) {
            if ( $cdt['in_quote'] == 'none' ) {
                $qcdts[] = "has('in_quote', null)";
            } else {
                $qcdts[] = "has('in_quote', 'true')";
            }
            unset($cdt['in_quote']);
        }

        if (isset($cdt['dowhile'])) {
            if ( $cdt['dowhile'] == 'false' ) {
                $qcdts[] = "has('dowhile', 'false')";
            } else {
                $qcdts[] = "has('dowhile', 'true')";
            }
            unset($cdt['dowhile']);
        }
        
        if (isset($cdt['filterOut'])) {
            if (is_string($cdt['filterOut'])) {
                // no check on atom here ? 
                $qcdts[] = "filter{it.token != '".$cdt['filterOut']."' }";
            } elseif (is_array($cdt['filterOut'])) {
                $qcdts[] = "filter{it.atom != null || !(it.token in ['".join("', '", $cdt['filterOut'])."'])}";
            } else {
                die("Unsupported type for filterOut\n");
            }

            unset($cdt['filterOut']);
        }

        if (isset($cdt['filterOut2'])) {
            if (is_string($cdt['filterOut2'])) {
                $qcdts[] = "filter{it.token != '".$cdt['filterOut2']."' }";
            } else {
                $qcdts[] = "filter{!(it.token in ['".join("', '", $cdt['filterOut2'])."'])}";
            }

            unset($cdt['filterOut2']);
        }

        if ($remainder = array_keys($cdt)) {
            print "Warning : the following ".count($remainder)." conditions were ignored : ".join(', ', $remainder)." (".get_class($this).")\n";
            print_r($cdt);
        }
        
        return $qcdts;
    }

    public function fullcode() {
        return '';
    }
}

?>