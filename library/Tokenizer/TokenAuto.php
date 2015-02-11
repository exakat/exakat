<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Tokenizer;

class TokenAuto extends Token {
    static public $round  = -1;
    protected $conditions = array();
    protected $actions    = array();
    protected $setAtom    = false;
    public $total         = null ;
    public $done          = null ;

    public function _check() {
        return false;
    }
    
    public function prepareQuery() {
        $query = ' total = 0; done = 0; ';
        $class = str_replace('Tokenizer\\', '', get_class($this));
        if (in_array($class, array('FunctioncallArray'))) {
            $query .= 'g.idx("racines")[["token":"S_ARRAY"]].out("INDEXED")';
        } elseif (in_array($class, Token::$types)) {
            $query .= "g.idx('racines')[['token':'$class']].out('INDEXED')";
        } else {
            die("Should only use atoms!");
        }
        $query .= '.sideEffect{ total++; }';

        $queryConditions = array();
        
        if (!empty($this->conditions[0])) {
            $queryConditions = array_merge($queryConditions, $this->readConditions($this->conditions[0]));
            
            $queryConditions[] = 'as("origin")';
            unset($this->conditions[0]);
        }

        for($i = -8; $i < 0; $i++) {
            if (!empty($this->conditions[$i])) {
                $conditions = $this->conditions[$i];
                $conditions['previous'] = abs($i);
                $queryConditions = array_merge($queryConditions, $this->readConditions($conditions));

                $queryConditions[] = "back('origin')";
            }
            unset($this->conditions[$i]);
        }

        for($i = 1; $i < 12; $i++) {
            if (!empty($this->conditions[$i])) {
                $conditions = $this->conditions[$i];
                $conditions['next'] = $i;
                $queryConditions = array_merge($queryConditions, $this->readConditions($conditions));

                $queryConditions[] = "back('origin')";
            }
            unset($this->conditions[$i]);
        }
        
        if (!empty($this->conditions)) {
            print "Some condition were not used! \n".__METHOD__."\n";
            die();
        }
        
        $query = $query.'.'.implode('.', $queryConditions);
        
        $this->setAtom = false;
        $qactions = $this->readActions($this->actions);
        $query .= ".each{\n done++; fullcode = it; fullcode.round = ".(self::$round).';
'.implode(";\n", $qactions).'; '.($this->setAtom ? $this->fullcode() : '' )."\n}; [total:total, done:done];";
        
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
//        $qactions[] = "\n it.setProperty('modifiedBy', '".str_replace('Tokenizer\\', '', get_class($this))."'); \n";

        if (isset($actions['keepIndexed'])) {
            if (!$actions['keepIndexed']) { // true means All
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
            $this->setAtom = true;
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
                    $d = str_repeat(".out('NEXT')", $destination).'.next()';
                } elseif ($destination < 0) {
                    $d = str_repeat(".in('NEXT')", $destination).'.next()';
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
            $token = new _Ppp(Token::$client);
            $fullcode = $token->fullcode();
            
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
            $token = new _Ppp(Token::$client);
            $fullcode = $token->fullcode();

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

        if (isset($actions['toGlobal'])) {
            $globalAtom = new _Global(Token::$client);
            $fullcode = $globalAtom->fullcode();

            $qactions[] = "
/* to global without arguments */

fullcode = it;
arg = it.out('NEXT').next();
arg.out('ARGUMENT').each{
    g.addEdge(fullcode, it, 'GLOBAL');
}

g.addEdge(fullcode, arg.out('NEXT').next(), 'NEXT');
arg.bothE('ARGUMENT').each{ g.removeEdge(it); }
g.removeVertex(arg);

$fullcode
";
            unset($actions['toGlobal']);
        }

        if (isset($actions['to_var_ppp'])) {
            $token = new _Ppp(Token::$client);
            $fullcode = $token->fullcode();

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

x = g.addVertex(null, [code:'', atom:'String', token:'T_STRING', virtual:true, line:it.line, fullcode:'', noDelimiter:'']);

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
x.inE('INDEXED').each{ g.removeEdge(it); }
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
$fullcode
";
            unset($actions['to_ppp']);
        }

        if (isset($actions['to_option'])) {
            $position = str_repeat(".out('NEXT')", $actions['to_option']);

            $token = new _Ppp(Token::$client);
            $fullcode = $token->fullcode();
            
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
            $token = new _Ppp(Token::$client);
            $fullcode = $token->fullcode();

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
$fullcode
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
a$c.inE('INDEXED').each{ g.removeEdge(it); }
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
b$d.inE('INDEXED').each{ g.removeEdge(it); }
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
it.inE('INDEXED').each{ g.removeEdge(it); }

g.removeVertex(it);
g.addEdge(a, b, 'NEXT');
";
                        $this->setAtom = false;
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
            $fullCodeSequence = $sequence->fullcode();
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* transform to const a=1 ,  b=2 => const a=1; const b=2 */

sequence = g.addVertex(null, [code:';', fullcode:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);

g.addEdge(g.idx('racines')[['token':'Sequence']].next(), sequence, 'INDEXED');

fullcode = sequence;
$fullCodeSequence

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
        p.bothE('NEXT').each{ g.removeEdge(it); }
        p.bothE('INDEXED').each{ g.removeEdge(it); }
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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
//semicolon.setProperty('modifiedBy', 'to_void');


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
        
        if (isset($actions['to_block'])) {
            $qactions[] = "
/* to_block */

it.setProperty('block', 'true');
next = it.out('NEXT').next();

if (next.atom == 'Sequence' || next.atom == 'SequenceCaseDefault') {
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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
    nextnext.outE('INDEXED').each{ g.removeEdge(it); }
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
                                                        'Postplusplus', 'Preplusplus', 'Return', 'Class', 'Phpcode', 'Functioncall', 'Shell' ] &&
                                      it.getProperty('token') != 'T_ELSEIF'}.any() &&
    it.in('NEXT').in('NEXT').filter{ !(it.getProperty('token') in ['T_ECHO', 'T_PRINT', 'T_AND_EQUAL', 'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL',
                                                    'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL',
                                                    'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
                                                    'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                                    'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                                    'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR', 'T_NEW',
                                                    'T_ARRAY_CAST','T_BOOL_CAST', 'T_DOUBLE_CAST','T_INT_CAST','T_OBJECT_CAST','T_STRING_CAST','T_UNSET_CAST',
                                                    'T_INSTANCEOF', 'T_INSTEADOF', 'T_QUESTION', 'T_DOT', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
                                                    'T_ELSE', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR']) || (it.getProperty('atom') != null && it.atom != 'Parenthesis')}.any() &&
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
    suivant.bothE('INDEXED').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', suivant);
//    suivant.setProperty('checkForNext', 'Next sequence');
}

// lone instruction AFTER
while (it.out('NEXT').filter{ it.atom in ['RawString', 'For', 'Phpcode', 'Function', 'Ifthen', 'Switch', 'Foreach',
                                       'Dowhile', 'Try', 'Class', 'Interface', 'Trait', 'While', 'Break', 'Assignation', 'Halt',
                                       'Staticmethodcall', 'Namespace', 'Label', 'Postplusplus', 'Preplusplus', 'Include', 'Functioncall',
                                       'Methodcall', 'Variable', 'Label', 'Goto', 'Static', 'New', 'Void', 'Identifier', 'Shell', 'Heredoc' ] &&
                                       it.token != 'T_ELSEIF' }.any() &&
    it.out('NEXT').out('NEXT').filter{!(it.token in ['T_CATCH', 'T_FINALLY', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' ,
                                                     'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                                     'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                                     'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR', 'T_AS',
                                                     'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_INSTANCEOF', 'T_QUESTION', 
                                                     'T_COLON', 'T_DOT'])}.
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', semicolon);
}

// cleaning INDEXED links when there are no more NEXT
if (it.both('NEXT').count() == 0) {
    it.inE('INDEXED').each{ g.removeEdge(it); }
}

";
            unset($actions['checkForNext']);
        }
        
        if (isset($actions['insertGlobalNs'])) {
            $qactions[] = "
/* insert global namespace */
x = g.addVertex(null, [code:'Global', atom:'Identifier', token:'T_STRING', virtual:true, line:it.line, fullcode:'Global']);

g.addEdge(x, it.out('NEXT').next(), 'NEXT');
it.outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, x, 'NEXT');

";
            unset($actions['insertGlobalNs']);
        }
        
        if (isset($actions['to_specialmethodcall'])) {
            $qactions[] = "
/* to_specialmethodcall */

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
            $this->setAtom = true;
            unset($actions['to_typehint']);
        }
        
        if (isset($actions['fullcode'])) {
            $this->setAtom = true;
            unset($actions['fullcode']);
        }
        
        if (isset($actions['insertEdge'])) {
            foreach($actions['insertEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                
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

        if (isset($actions['to_concatenation']) && $actions['to_concatenation']) {
                $qactions[] = "
/* to Concatenation */

x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
g.idx('atoms').put('atom', 'Concatenation', x)

// initial
rank = 0;
g.addEdge(x, b1, 'CONCAT');
b1.setProperty('rank', rank);
b1.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(b2, x, 'NEXT');

while(a2.token == 'T_DOT') {
    g.addEdge(x, a1, 'CONCAT');
    rank += 1;
    a1.setProperty('rank', rank);
    a2.inE('INDEXED').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', a2);

    // prepare next round
    a3 = a2.out('NEXT').next();
    a4 = a3.out('NEXT').next();

    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a2.bothE('NEXT').each{ g.removeEdge(it); }

    a1 = a3;
    a2 = a4;
}

g.addEdge(x, a1, 'CONCAT');
rank += 1;
a1.setProperty('rank', rank);
a1.bothE('NEXT').each{ g.removeEdge(it); }

a2.inE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', it);
g.addEdge(x, a2, 'NEXT');

x.out('CONCAT').inE('INDEXED').each{ g.removeEdge(it); }

fullcode = x;

";
            unset($actions['to_concatenation']);
        }

        if (isset($actions['to_argument']) && $actions['to_argument']) {
                $qactions[] = "
/* to Argument */

x = g.addVertex(null, [code:'Arguments', atom:'Arguments', token:'T_COMMA', virtual:true, line:it.line]);
g.idx('atoms').put('atom', 'Arguments', x)

// initial
rank = 0;
g.addEdge(x, b1, 'ARGUMENT');
b1.setProperty('rank', rank);
b1.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(b2, x, 'NEXT');

while(a2.token == 'T_COMMA') {
    g.addEdge(x, a1, 'ARGUMENT');
    rank += 1;
    a1.setProperty('rank', rank);
    a2.bothE('INDEXED').each{ g.removeEdge(it); }
    g.idx('delete').put('node', 'delete', a2);

    // prepare next round
    a3 = a2.out('NEXT').next();
    a4 = a3.out('NEXT').next();

    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a2.bothE('NEXT').each{ g.removeEdge(it); }

    a1 = a3;
    a2 = a4;
}

g.addEdge(x, a1, 'ARGUMENT');
rank += 1;
a1.setProperty('rank', rank);
a1.bothE('NEXT').each{ g.removeEdge(it); }

a2.inE('NEXT').each{ g.removeEdge(it); }
g.idx('delete').put('node', 'delete', it);
g.addEdge(x, a2, 'NEXT');

x.out('ARGUMENT').inE('INDEXED').each{ g.removeEdge(it);  }

fullcode = x;

";
            unset($actions['to_argument']);
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
//        it.out('$link').has('rank', 1).next().setProperty('rankedby', 'zero_is_multiple');
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
//        it.out('$link').has('rank', 0).next().setProperty('rankedby', 'one_is_multiple');
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
//        it.out('$link').each{ it.setProperty('rankedby', 'both_are_single')};
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
    it.bothE('INDEXED').each{ g.removeEdge(it) ; }
    
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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
            $qactions[] = "
/* to_block_foreach */

x = g.addVertex(null, [code:'Block with Foreach', fullcode:'Block with Foreach', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, line:it.line, block:'true', fullcode:' /**/ ']);
g.addEdge(g.idx('racines')[['token':'Sequence']].next(), x, 'INDEXED');

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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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

x = g.addVertex(null, [code:'Block with control if elseif', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, fullcode:' /**/ ', block:'true' ]);
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
    it.out('EXTENDS').sideEffect{ args = it; }.out('ARGUMENT').each{
        g.addEdge(args, it, 'EXTENDS');
        it.inE('ARGUMENT').each{ g.removeEdge(it); }
    }
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
    it.bothE('INDEXED').each{ g.removeEdge(it); }
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
    it.bothE('INDEXED').each{ g.removeEdge(it); }
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            unset($actions['createVoidForCase']);
        }

        if (isset($actions['to_functioncall']) && $actions['to_functioncall']) {
            $qactions[] = "
/* to_functioncall */
a2.setProperty('atom', 'Functioncall')
g.addEdge(a2, a5, 'ARGUMENTS');
g.addEdge(it, a2, 'NEXT');
g.addEdge(a2, a6.out('NEXT').next(), 'NEXT');

a1.bothE('NEXT').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }
a4.bothE('NEXT').each{ g.removeEdge(it); }
a6.bothE('NEXT').each{ g.removeEdge(it); }

g.removeVertex(a1);
g.removeVertex(a3);
g.removeVertex(a4);
g.removeVertex(a6);

g.idx('atoms').put('atom', 'Functioncall', a2)
            ";
            unset($actions['to_functioncall']);
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

            ";
            unset($actions['createVoidForDefault']);
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
        cds = g.addVertex(null, [code:'Sequence Case Default', atom:'SequenceCaseDefault', token:'T_SEQUENCE_CASEDEFAULT', virtual:true, line:it.line, fullcode:' /**/ ']);

        it.setProperty('rank', 0);

        g.addEdge(it.in('NEXT').next(), cds, 'NEXT');
        g.addEdge(cds, it.out('NEXT').next(), 'NEXT');
        g.addEdge(cds, it, 'ELEMENT');
    
        it.bothE('NEXT').each{ g.removeEdge(it); }
    }

    
GREMLIN;
            unset($actions['caseDefaultSequence']);
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
block = g.addVertex(null, [code:'Block with Foreach', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, fullcode:'{ /**/ } '
/*, modifiedBy:'_Foreach' */ ]);
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

x = g.addVertex(null, [code:'Block with While', token:'T_SEMICOLON', atom:'Sequence', virtual:true, block:'true', line:it.line, fullcode:' /**/ '
/*, modifiedBy:'_While' */]);
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
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
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

        if (isset($actions['to_methodcall'])) {
            $string = new Methodcall(Token::$client);
            $fullCodeString = $string->fullcode();

            $qactions[] = "
/* to_methodcall */

x = it;
initial = it;
a2 = a1.out('NEXT').next();

while(x.token == 'T_OBJECT_OPERATOR' && a1.atom == 'Functioncall') {
    g.addEdge(x, b1, 'OBJECT');
    g.addEdge(x, a1, 'METHOD');
    
    x.out.inE('INDEXED').each{ g.removeEdge(it);}
    x.setProperty('atom', 'Methodcall');
    g.idx('atoms').put('atom', 'Methodcall', x);
    
    fullcode = x;
    $fullCodeString
    
    b1.bothE('NEXT').each{ g.removeEdge(it);}
    x.bothE('NEXT').each{ g.removeEdge(it);}
    a1.bothE('NEXT').each{ g.removeEdge(it);}

    b1 = x;
    x  = a2;
    a1 = x.out('NEXT').next();
    a2 = a1.out('NEXT').next();
}

g.addEdge(b2, b1, 'NEXT'); // needed because b2 -> b1
g.addEdge(b1, x, 'NEXT');

";
            unset($actions['to_methodcall']);
        }

        if (isset($actions['makeSequence'])) {
            $it = $actions['makeSequence'];

            $makeSequence = <<<GREMLIN
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
        $it.bothE('NEXT').each{ g.removeEdge(it); }
        sequence2.bothE('INDEXED').each{ g.removeEdge(it); }
        g.idx('delete').put('node', 'delete', sequence2);
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
GREMLIN;

            if (isset($actions['makeSequenceAlways']) && $actions['makeSequenceAlways']) {
                $qactions[] = "
/* makeSequence Always */
$makeSequence
";
                unset($actions['makeSequenceAlways']);
            } else {
                $qactions[] = "
/* makeSequence */

list_before = ['T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER',
        'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE',
        'T_AND_EQUAL', 'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
        'T_COMMA', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
        'T_OPEN_BRACKET', 'T_CLOSE_BRACKET',
        'T_ECHO', 'T_PRINT', 'T_GLOBAL',
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
        'T_AT', 'T_CASE', 'T_TILDE',
        'T_ARRAY_CAST','T_BOOL_CAST', 'T_DOUBLE_CAST','T_INT_CAST','T_OBJECT_CAST','T_STRING_CAST','T_UNSET_CAST',
        'T_DO', 'T_TRY',
        'T_STRING', 'T_INSTEADOF', 'T_INSTANCEOF', 'T_BANG',
        'T_ELSE', 'T_INC', 'T_DEC', 'T_IF', 'T_ELSEIF',
        'T_CONST', 'T_FUNCTION', 'T_FINALLY'
        ];

list_after = [
        'T_COMMA', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
        'T_OPEN_BRACKET', 'T_CLOSE_BRACKET',
        'T_PLUS', 'T_MINUS',
        'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_POW',
        'T_COLON', 'T_NEW', 'T_DOT', 'T_DOUBLE_ARROW',
        'T_SR','T_SL', 'T_CURLY_OPEN',
        'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
        'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
        'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR',
        'T_INSTEADOF', 

        'T_ELSE', 'T_ELSEIF',
        'T_CATCH'];

list_after_token = [
        'T_OBJECT_OPERATOR', 'T_INC', 'T_DEC',
        'T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER',
        'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL', 'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_POW_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
        'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
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
    && !($it.in('NEXT').next().token in ['T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS', 'T_COMMA', 'T_STRING', 'T_NS_SEPARATOR', 'T_CALLABLE'])
    && !($it.in('NEXT').has('token', 'T_OPEN_CURLY').any() && $it.in('NEXT').in('NEXT').filter{ it.token in ['T_VARIABLE', 'T_OPEN_CURLY', 'T_CLOSE_CURLY', 'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOLLAR', 'T_DOUBLE_COLON']}.any()) /* \$x{\$b - 2} */
    ) {
$makeSequence;
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
            }
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

        if (isset($actions['make_quoted_string'])) {
            $atom = $actions['make_quoted_string'];
            $class = "\\Tokenizer\\$atom";
            $string = new $class(Token::$client);
            $fullCodeString = $string->fullcode();
            
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

it.setProperty('atom', '$atom');

fullcode = x;
$fullCodeString

it.setProperty('fullcode', x.fullcode);

/* Clean index */
x.out('CONCAT').each{
    it.inE('INDEXED').each{
        g.removeEdge(it);
    }
}

/* indexing */  g.idx('atoms').put('atom', '$atom', it);

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
        
        if (isset($actions['addToIndex'])) {
            list(, $token) = each($actions['addToIndex']);
            $qactions[] = "
/* add to the following index */

g.addEdge(g.idx('racines')[['token':'$token']].next(), it, 'INDEXED');

";
            unset($actions['addToIndex']);
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

x = g.addVertex(null, [code:it.code, fullcode: it.code, atom:'Variable', token:'T_VARIABLE', virtual:true, line:it.line
/*, modifiedBy:'FunctionCall'*/ ]);
g.addEdge(it, x, 'NAME');
g.idx('atoms').put('atom', 'Variable', x);
                ";
            unset($actions['variable_to_functioncall']);
        }

        if (isset($actions['array_to_functioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* hold the array as property.  */

x = g.addVertex(null, [code:it.code, fullcode: it.fullcode, atom:'Array', token:'T_OPEN_BRACKET', virtual:true, line:it.line
/*,  modifiedBy:'FunctionCallArray' */]);
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
'YIELD', 'GLOBAL').each{
    it.inE('INDEXED').each{
        g.removeEdge(it);
    }
}
                ";
            unset($actions['cleanIndex']);
        }

        if ($remainder = array_keys($actions)) {
            print 'Warning : the following '.count($remainder).' actions were ignored : '.implode(', ', $remainder)."\n";
        }

        return $qactions;
    }

    private function readConditions($conditions) {
        $queryConditions = array();

        if (isset($conditions['next'])) {
            for($i = 0; $i < $conditions['next']; $i++) {
                $queryConditions[] = "out('NEXT')";
            }
            $queryConditions[] = "sideEffect{ a{$conditions['next']} = it;}";
            unset($conditions['next']);
        }

        if (isset($conditions['previous'])) {
            for($i = 0; $i < $conditions['previous']; $i++) {
                $queryConditions[] = "in('NEXT')";
            }
            $queryConditions[] = "sideEffect{ b{$conditions['previous']} = it;}";
            unset($conditions['previous']);
        }

        if (isset($conditions['property'])) {
            foreach($conditions['property'] as $property => $value) {
                if (is_array($value)) {
                    $queryConditions[] = "filter{it.$property in ['".implode("', '", $value)."']}";
                } else {
                    $queryConditions[] = "has('$property', '$value')";
                }
            }
            unset($conditions['property']);
        }

        if (isset($conditions['check_for_string'])) {
            if (is_array($conditions['check_for_string'])) {
                $classes = "'".implode("', '", $conditions['check_for_string'])."'";
            } else {
                $classes = "'".$conditions['check_for_string']."'";
            }
            $queryConditions[] = "as('cfs').out('NEXT').filter{ it.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'] || it.atom in [$classes] }.loop(2){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.back('cfs')";

            unset($conditions['check_for_string']);
        }

        if (isset($conditions['check_for_arguments'])) {
            if (is_array($conditions['check_for_arguments'])) {
                $classes = "'".implode("', '", $conditions['check_for_arguments'])."'";
            } else {
                $classes = "'".$conditions['check_for_arguments']."'";
            }

            $finalTokens = array_merge( Token::$alternativeEnding,
                            array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON', 'T_CLOSE_TAG', 'T_OPEN_CURLY', 'T_INLINE_HTML', 'T_CLOSE_BRACKET'));
            $finalTokens = "'".join("', '", $finalTokens)."'";
            $queryConditions[] = "filter{ it.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA'] || it.atom in [$classes] }.loop(2){!(it.object.token in [$finalTokens])}.filter{ !(it.token in ['T_OPEN_CURLY'])}.any() }";

            unset($conditions['check_for_arguments']);
        }

        if (isset($conditions['check_for_namelist'])) {
            if (is_array($conditions['check_for_namelist'])) {
                $classes = "'".implode("', '", $conditions['check_for_namelist'])."'";
            } else {
                $classes = "'".$conditions['check_for_namelist']."'";
            }

            $finalTokens = array_merge( Token::$alternativeEnding,
                            array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON', 'T_CLOSE_TAG', 'T_OPEN_CURLY', 'T_CLOSE_BRACKET'));
            $finalTokens = "'".join("', '", $finalTokens)."'";
            $queryConditions[] = "filter{ it.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA'] || it.atom in [$classes] }.loop(2){!(it.object.token in [$finalTokens])}.any() }";

            unset($conditions['check_for_namelist']);
        }

        if (isset($conditions['check_for_concatenation'])) {
            if (is_array($conditions['check_for_concatenation'])) {
                $classes = "'".implode("', '", $conditions['check_for_concatenation'])."'";
            } else {
                $classes = "'".$conditions['check_for_concatenation']."'";
            }
            
            $finalTokens = array_merge(Token::$alternativeEnding,
                           array('T_SEMICOLON', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_BRACKET', 'T_DOUBLE_ARROW', 'T_COMMA',
                                 'T_CLOSE_TAG', 'T_COLON', 'T_QUESTION', 'T_QUESTION',
                                 'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND', 'T_OR',
                                 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                 'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR',
                                 'T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL',
                                 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER', 'T_CLOSE_CURLY',
                                 'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_PLUS','T_MINUS', 'T_POW', 'T_ELSEIF'));
            $finalTokens = "'".join("', '", $finalTokens)."'";

//            $queryConditions[] = "as('cfc').out('NEXT').filter{ it.token in [$finalTokens, 'T_DOT'] || it.atom in [$classes] }.loop(2){!(it.object.token in [$finalTokens])}.filter{it.out('NEXT').next().atom != null || it.out('NEXT').next().token in ['T_OPEN_CURLY']}.back('cfc')";
            $queryConditions[] = "filter{ it.out('NEXT').filter{ it.token in [$finalTokens, 'T_DOT'] || it.atom in [$classes] }.loop(2){!(it.object.token in [$finalTokens])}.filter{ !(it.token in ['T_OPEN_CURLY'])}.any() }";

            unset($conditions['check_for_concatenation']);
        }

        if (isset($conditions['code'])) {
            if (is_array($conditions['code']) && !empty($conditions['code'])) {
                $queryConditions[] = "filter{it.code in ['".implode("', '", $conditions['code'])."']}";
            } else {
                $queryConditions[] = "has('code', '".$conditions['code']."')";
            }
            unset($conditions['code']);
        }

        if (isset($conditions['icode'])) {
            if (is_array($conditions['icode']) && !empty($conditions['icode'])) {
                $queryConditions[] = "hasNot('code', null).filter{it.code.toLowerCase() in ['".implode("', '", $conditions['icode'])."']}";
            } else {
                $queryConditions[] = "hasNot('code', null).filter{it.code.toLowerCase() == '".$conditions['icode']."'}";
            }
            unset($conditions['icode']);
        }

        if (isset($conditions['notcode']) && is_array($conditions['notcode']) && !empty($conditions['notcode'])) {
            $queryConditions[] = "filter{!(it.code in ['".implode("', '", $conditions['notcode'])."'])}";
            unset($conditions['notcode']);
        }

        if (isset($conditions['token'])) {
            if ( is_array($conditions['token']) && !empty($conditions['token'])) {
                $queryConditions[] = "filter{it.token in ['".implode("', '", $conditions['token'])."']}";
            } else {
                $queryConditions[] = "has('token', '".$conditions['token']."')";
            }
            unset($conditions['token']);
        }

        if (isset($conditions['notToken'])) {
            if ( is_array($conditions['notToken']) && !empty($conditions['notToken'])) {
                $queryConditions[] = "filter{!(it.token in ['".implode("', '", $conditions['notToken'])."'])}";
            } else {
                $queryConditions[] = "hasNot('token', '".$conditions['notToken']."')";
            }
            unset($conditions['notToken']);
        }
        
        if (isset($conditions['atom'])) {
            if ( is_array($conditions['atom']) && !empty($conditions['atom'])) {
                $queryConditions[] = "filter{it.atom in ['".implode("', '", $conditions['atom'])."']}";
            } elseif ( is_string($conditions['atom']) && $conditions['atom'] == 'none') {
                $queryConditions[] = "has('atom', null)";
            } elseif ( is_string($conditions['atom']) && $conditions['atom'] == 'yes') {
                $queryConditions[] = "hasNot('atom', null)";
            } else {
                $queryConditions[] = "has('atom', '".$conditions['atom']."')";
            }
            unset($conditions['atom']);
        }

        if (isset($conditions['notAtom'])) {
            if ( is_array($conditions['notAtom']) && !empty($conditions['notAtom'])) {
                $queryConditions[] = "filter{!(it.atom in ['".implode("', '", $conditions['notAtom'])."'])}";
            } else {
                $queryConditions[] = "hasNot('atom', '".$conditions['notAtom']."')";
            }
            unset($conditions['notAtom']);
        }

        if (isset($conditions['in_quote'])) {
            if ( $conditions['in_quote'] == 'none' ) {
                $queryConditions[] = "has('in_quote', null)";
            } else {
                $queryConditions[] = "has('in_quote', 'true')";
            }
            unset($conditions['in_quote']);
        }

        if (isset($conditions['dowhile'])) {
            if ( $conditions['dowhile'] == 'false' ) {
                $queryConditions[] = "has('dowhile', 'false')";
            } else {
                $queryConditions[] = "has('dowhile', 'true')";
            }
            unset($conditions['dowhile']);
        }
        
        if (isset($conditions['filterOut'])) {
            if (is_string($conditions['filterOut'])) {
                // no check on atom here ?
                $queryConditions[] = "filter{it.token != '".$conditions['filterOut']."' }";
            } elseif (is_array($conditions['filterOut'])) {
                $queryConditions[] = "filter{it.atom != null || !(it.token in ['".implode("', '", $conditions['filterOut'])."'])}";
            } else {
                die("Unsupported type for filterOut\n");
            }

            unset($conditions['filterOut']);
        }

        if (isset($conditions['filterOut2'])) {
            if (is_string($conditions['filterOut2'])) {
                $queryConditions[] = "filter{it.token != '".$conditions['filterOut2']."' }";
            } else {
                $queryConditions[] = "filter{!(it.token in ['".implode("', '", $conditions['filterOut2'])."'])}";
            }

            unset($conditions['filterOut2']);
        }

        if ($remainder = array_keys($conditions)) {
            print 'Warning : the following '.count($remainder).' conditions were ignored : '.implode(', ', $remainder).' ('.get_class($this).")\n";
            print_r($conditions);
        }
        
        return $queryConditions;
    }

    public function fullcode() {
        return '';
    }
}

?>
