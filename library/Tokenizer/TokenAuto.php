<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

abstract class TokenAuto extends Token {
    static    public    $round      = -1;
              protected $conditions = array();
              protected $actions    = array();
              protected $setAtom    = false;
              public    $total      = null ;
              public    $done       = null ;
              public    $cycles     = null ;
              protected $queries    = array();
    
    const CYCLE_COUNT = 20;
    const CYCLE_SIZE  = 2000;

    public function _check() {
        return false;
    }
    
    public function prepareQuery() {
        $query = ' total = 0; done = 0; toDelete = []; ';
        $class = str_replace('Tokenizer\\', '', get_class($this));

        $moderator = '';
        $moderatorFinal = '[0..'.self::CYCLE_SIZE.']';

        if (in_array($class, array('FunctioncallArray'))) {
            $query .= 'g.idx("racines")[["token":"S_ARRAY"]].out("INDEXED")'.$moderator;
        } elseif (in_array($class, array('Staticconstant','Staticmethodcall','Staticproperty'))) {
            $query .= 'g.idx("racines")[["token":"Staticproperty"]].out("INDEXED")'.$moderator;
        } elseif (in_array($class, array('Property','Methodcall'))) {
            $query .= 'g.idx("racines")[["token":"Property"]].out("INDEXED")'.$moderator;
        } elseif (in_array($class, Token::$types)) {
            $query .= 'g.idx("racines")[["token":"'.$class.'"]].out("INDEXED")'.$moderator;
        } else {
            die("Should only use atoms!");
        }
        $query .= '.sideEffect{ total++; }';

        $q = array();
        if (!empty($this->conditions[0])) {
            $q[] = $this->readConditions($this->conditions[0]);
            $q[] = ['as("origin")'];

            unset($this->conditions[0]);
        }

        for($i = -8; $i < 0; ++$i) {
            if (!empty($this->conditions[$i])) {
                $conditions = $this->conditions[$i];
                $conditions['previous'] = abs($i);

                $q[] = $this->readConditions($conditions);
                $q[] = ['back("origin")'];
            }
            unset($this->conditions[$i]);
        }

        for($i = 1; $i < 12; ++$i) {
            if (!empty($this->conditions[$i])) {
                $conditions = $this->conditions[$i];
                $conditions['next'] = $i;

                $q[] = $this->readConditions($conditions);
                $q[] = ['back("origin")'];
            }
            unset($this->conditions[$i]);
        }

        $queryConditions = call_user_func_array('array_merge', $q);
        unset($q);
        
        if (!empty($this->conditions)) {
            throw new UnprocessedCondition();
        }
        
        $query .= '.'.implode('.', $queryConditions);
        
        $this->setAtom = false;
        $qactions = $this->readActions($this->actions);
        $query .= $moderatorFinal.'.each{ done++; fullcode = it;
'.implode(";\n", $qactions).'; '.($this->setAtom ? $this->fullcode() : '' )."\n};
toDelete.each{ g.removeVertex(it); }
[total:total, done:done];";
        
        return $query;
    }

    public function printQuery() {
        $query = $this->prepareQuery();
        
        die( $query.__METHOD__);
    }

    public function checkAuto() {
        $this->queries[] = $this->prepareQuery();
    }
    
    public function execQueries() {
        foreach($this->queries as $query) {
            $this->cycles = 0;
            $this->total  = 0;
        
            do {
                $begin = microtime(true);
                $res = $this->gremlin->query($query);
                if (!isset($res->results)) {
                    echo $query, "\n",  print_r($res, true);
                    die();
                }
                $res = $res->results[0];

                $end = microtime(true);
            
                if (!isset($res->done)) {
                    echo __METHOD__, "\n", $query, print_r($res, true);
                    die();
                }

                $this->total += (int) $res->total;
                $this->done  += (int) $res->done;
                ++$this->cycles;
                display('Cycle '.get_class($this).' '.$this->cycles.' '.$res->done.' '.number_format(($end - $begin) * 1000, 0)."\n");
            } while ($res->done > self::CYCLE_SIZE && $this->cycles < self::CYCLE_COUNT);
        }
    }

    private function readActions($actions) {
        $qactions = array();

        // @doc audit trail track
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

        if (isset($actions['minusIntval'])) {
            $qactions[] = " /* minusIntval */\n   if (it.intval != null) {
    it.intval = -1 * it.intval;
}

";
            unset($actions['minusIntval']);
        }
        
        if (isset($actions['property'])) {
            if (is_array($actions['property']) && !empty($actions['property'])) {
                foreach($actions['property'] as $name => $value) {
                    if ($value === true) {
                        $qactions[] = " /* property */   it.setProperty('$name', true)";
                    } elseif ($value === false) {
                        $qactions[] = " /* property */   it.setProperty('$name', false)";
                    } else {
                        $qactions[] = " /* property */   it.setProperty('$name', '$value')";
                    }
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
                    } elseif (substr($value, 0, 3) == '"&"') {
                        // Just let it go
                    } elseif ($value === true) {
                        $value = 'true';
                    } else {
                        $value = "'$value'";
                    }
                    $qactions[] .= "
fullcode.setProperty('$name', $value)";
                }
            }
            unset($actions['propertyNext']);
        }

        if (isset($actions['propertyPrev'])) {
            if (is_array($actions['propertyPrev']) && !empty($actions['propertyPrev'])) {
                $qactions[] = " /* propertyPrev */
fullcode = it.in('NEXT').next(); \n";
                foreach($actions['propertyPrev'] as $name => $value) {
                    if (substr($value, 0, 3) == 'it.') {
                        $value = 'fullcode.' . substr($value, 3);
                    } elseif (substr($value, 0, 3) == '"&"') {
                        // Just let it go
                    } elseif ($value === true) {
                        $value = 'true';
                    } else {
                        $value = "'$value'";
                    }
                    $qactions[] .= "
fullcode.setProperty('$name', $value)";
                }
            }
            unset($actions['propertyPrev']);
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

        if (isset($actions['toVarNew'])) {
            $token = new _Ppp($this->gremlin);
            $fullcode = $token->fullcode();
            
            $atom = $actions['toVarNew'];
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
    ppp = g.addVertex(null, [code:'ppp', atom:'Visibility', token:token, virtual:true, line:it.line, fullcode:'Visibility']);
    g.idx('atoms').put('atom','Visibility', ppp);

    var.out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{
        option = g.addVertex(null, [code:it.code, fullcode:it.code, atom:it.atom, token:it.token, virtual:true, line:it.line]);
        g.addEdge(ppp, option, it.code.toUpperCase());
    }

    g.addEdge(root, ppp, 'ELEMENT');
    ppp.setProperty('rank', it.rank);

    g.addEdge(ppp, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
    g.idx('atoms').put('atom', 'Void', tvoid);

    g.addEdge(ppp, tvoid, 'VALUE');

    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line, fullcode: var.code]);
    g.addEdge(ppp, tstatic, var.code.toUpperCase());

    fullcode = ppp;
    $fullcode
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    ppp = g.addVertex(null, [code:'ppp', atom:'Visibility', token:token, virtual:true, line:it.line, fullcode: var.code]);
    var.out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{
        option = g.addVertex(null, [code:it.code, fullcode:it.code, atom:it.atom, token:it.token, virtual:true, line:it.line]);
        g.addEdge(ppp, option, it.code.toUpperCase());
    }
    g.idx('atoms').put('atom','Visibility', ppp);

    ppp.setProperty('rank', it.rank);
    g.addEdge(root, ppp, 'ELEMENT');

    g.addEdge(ppp, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(ppp, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line, fullcode:var.code]);
    g.addEdge(ppp, tstatic, var.code.toUpperCase());
    
    toDelete.push(it);

    fullcode = ppp;
    $fullcode
}

g.addEdge(var.in('NEXT').next(), root, 'NEXT');
g.addEdge(root, arg.out('NEXT').next(), 'NEXT');

var.bothE('NEXT').each{ g.removeEdge(it);}
arg.outE('NEXT').each{ g.removeEdge(it);}

arg.out('ARGUMENT').out('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{ g.removeVertex(it); }
arg.out('ARGUMENT').outE('PUBLIC', 'PRIVATE', 'PROTECTED', 'STATIC').each{ g.removeEdge(it); }

var.out('STATIC', 'PRIVATE', 'PUBLIC', 'PROTECTED').each{ g.removeVertex(it); }
var.outE('STATIC', 'PRIVATE', 'PUBLIC', 'PROTECTED').each{ g.removeEdge(it); }

g.removeVertex(var);
g.removeVertex(arg);

";
            unset($actions['toVarNew']);
        }

        if (isset($actions['toGlobal'])) {
            $globalAtom = new _Global($this->gremlin);
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

        if (isset($actions['toUseConst'])) {
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
    
    g.removeVertex(a1);

";
            unset($actions['toUseConst']);
        }

        if (isset($actions['toUse'])) {
            $qactions[] = "
/* to use with arguments */
if (it.out('NEXT').next().token in ['T_CONST', 'T_FUNCTION']) {
    if (a1.token == 'T_CONST') {
        link = 'CONST';
    } else {
        link = 'FUNCTION';
    }
    
    it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
    g.addEdge(it, a2, 'NEXT');
    
    g.removeVertex(a1);
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
g.removeVertex(arg);

";
            unset($actions['toUse']);
        }

        if (isset($actions['toUseBlock'])) {
            $qactions[] = "
/* to use with arguments and block */
var = it;
arg = it.out('NEXT').next();

arg.out('ARGUMENT').each{
    g.addEdge(var, it, 'USE');
    g.removeEdge(it.inE('ARGUMENT').next());
}

oc = it.out('NEXT').out('NEXT').next();
block = oc.out('NEXT').next();
cc = block.out('NEXT').next();
next = cc.out('NEXT').next();

block.bothE('NEXT').each{ g.removeEdge(it); }
oc.bothE('NEXT').each{ g.removeEdge(it); }
cc.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(var, block, 'BLOCK');

toDelete.push(arg);
g.removeVertex(oc);
g.removeVertex(cc);
it.outE('NEXT').each{ g.removeEdge(it); }

g.addEdge(var, next, 'NEXT');

";
            unset($actions['toUseBlock']);
        }

        if (isset($actions['toLambda'])) {
            $qactions[] = "
/* to to_lambda function */

x = g.addVertex(null, [code:'', atom:'String', token:'T_STRING', virtual:true, line:it.line, fullcode:'', noDelimiter:'']);

g.addEdge(it, x, 'NAME');
it.setProperty('lambda', true);

if (a1.token == 'T_AND') {
    a1.bothE('NEXT').each{ g.removeEdge(it); }
    toDelete.push(a1);
    it.setProperty('reference', true);

    op = a2;
    args = a3;
    cp = a4;
} else {
    op = a1;
    args = a2;
    cp = a3;
}

g.addEdge(it, args, 'ARGUMENTS');

g.addEdge(it, cp.out('NEXT').next(), 'NEXT');

op.bothE('NEXT').each{ g.removeEdge(it); }
cp.bothE('NEXT').each{ g.removeEdge(it); }

toDelete.push(op);
toDelete.push(cp);

";
            unset($actions['toLambda']);
        }

        if (isset($actions['to_ppp'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "

/* to ppp alone */
x = g.addVertex(null, [code:it.code, atom:'Visibility', token:it.token, virtual:true, line:it.line, fullcode:it.code ]);

/* indexing */
g.idx('atoms').put('atom', 'Visibility', x);

g.addEdge(x, it.out('NEXT').next(), 'DEFINE');
it.out('NEXT').has('atom', 'Variable').each {
    tvoid = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
    g.idx('atoms').put('atom', 'Void', tvoid);

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

        if (isset($actions['toOption'])) {
            $position = str_repeat(".out('NEXT')", $actions['toOption']);

            $token = new _Ppp($this->gremlin);
            $fullcode = $token->fullcode();
            
            $qactions[] = "
/* turn the current token to an option of one of the next tokens (default 1)*/

ppp = it{$position}.next();

g.addEdge(ppp, it, it.code.toUpperCase());
g.addEdge(it.in('NEXT').next() , it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it); }
it.fullcode = it.code;
";
            unset($actions['toOption']);
        }
        
        if (isset($actions['to_ppp_assignation'])) {
            $token = new _Ppp($this->gremlin);
            $fullcode = $token->fullcode();

            $qactions[] = "
/* to ppp with assignation */

x = g.addVertex(null, [code:it.code, atom:'Visibility', token:it.token, virtual:true, line:it.line]);

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
g.idx('atoms').put('atom', 'Visibility', x);

fullcode = x;
$fullcode
";
            unset($actions['to_ppp_assignation']);
        }
        
        if (isset($actions['transform'])) {
            $c = 0;
            
            foreach($actions['transform'] as $destination => $label) {
                if ($label == 'NONE') {
                    continue;
                }

                // Destination > 0
                if ($destination > 0) {
                    ++$c;
                
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop out ($c) */
g.addEdge(a$c.in('NEXT').next(), a$c.out('NEXT').next(), 'NEXT');
a$c.bothE('NEXT').each{ g.removeEdge(it); }
a$c.inE('INDEXED').each{ g.removeEdge(it); }
toDelete.push(a$c);

";
                    } elseif ($label == 'PPP') {
                        $qactions[] = "
/* Build a link with the target's code */
target = a$c;

g.addEdge(it, target, target.code.toUpperCase());
g.addEdge(target.in('NEXT').next(), target.out('NEXT').next(), 'NEXT');
target.bothE('NEXT').each{ g.removeEdge(it); }
target.bothE('INDEXED').each{ g.removeEdge(it); }

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
toDelete.push(b$d);

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
                } elseif ($label == 'DROP') {
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

            unset($actions['transform']);
        }

        if (isset($actions['makeFromList'])) {
            $link = $actions['makeFromList'];
            
            // must be after transform
            $qactions[] = "
/* Move arguments under the keyword (IMPLEMENT, CONST) */

global = it;
rank = 0;

// first and n-1 -th round.
while(a2.token == 'T_COMMA') {
    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a1.bothE('INDEXED').each{ g.removeEdge(it); }
    a1.rank = rank;
    ++rank;
    g.addEdge(global, a1, '$link');
    
    toDelete.push(a2); // drop ,
    a1 = a2.out('NEXT').next();
    a2 = a1.out('NEXT').next();
}

a1.bothE('NEXT').each{ g.removeEdge(it); }
a1.bothE('INDEXED').each{ g.removeEdge(it); }
a1.rank = rank;
++rank;
g.addEdge(global, a1, '$link');

g.addEdge(it, a2, 'NEXT');

";
            unset($actions['makeFromList']);
        }

        if (isset($actions['makeNextFromList'])) {
            // must be after transform
            $qactions[] = "
/* Move arguments under the keyword (FUNCTION, CONST) */

global = it;
rank = 0;

link = a1.code.toUpperCase();
toDelete.push(a1);

a1 = a2;
a2 = a1.out('NEXT').next();

// first and n-1 -th round.
while(a2.token == 'T_COMMA') {
    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a1.bothE('INDEXED').each{ g.removeEdge(it); }
    a1.rank = rank;
    ++rank;
    g.addEdge(global, a1, link);
    
    toDelete.push(a2); // drop ,
    a1 = a2.out('NEXT').next();
    a2 = a1.out('NEXT').next();
}

a1.bothE('NEXT').each{ g.removeEdge(it); }
a1.bothE('INDEXED').each{ g.removeEdge(it); }
a1.rank = rank;
++rank;
g.addEdge(global, a1, link);

g.addEdge(it, a2, 'NEXT');

";
            unset($actions['makeNextFromList']);
        }

        if (isset($actions['makePpp'])) {
            
            // must be after transform
            $qactions[] = "
/* Move arguments under the keyword (IMPLEMENT, CONST) */

global = it;
link = 'DEFINE';
rank = 0;

// first and n-1 -th round.
while(a2.token == 'T_COMMA') {
    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a1.bothE('INDEXED').each{ g.removeEdge(it); }
    a1.rank = rank;
    ++rank;
    g.addEdge(global, a1, link);
    
    toDelete.push(a2); // drop ,
    a1 = a2.out('NEXT').next();
    a2 = a1.out('NEXT').next();
}

// last round
a1.bothE('NEXT').each{ g.removeEdge(it); }
a1.bothE('INDEXED').each{ g.removeEdge(it); }
a1.rank = rank;
g.addEdge(global, a1, link);

g.addEdge(it, a2, 'NEXT');

x = g.addVertex(null, [code:it.code, fullcode:it.code, token:it.token, atom:'Visibility', virtual:true, line:it.line]);
g.addEdge(it, x, it.code.toUpperCase());

";
            unset($actions['makePpp']);
        }

        if (isset($actions['toImplements'])) {
            $link = $actions['toImplements'];

            // must be after transform
            $qactions[] = "
/* Move arguments to implements */

classe = it;
rank = 0;

// first and n-1 -th round.
while(a3.token != 'T_OPEN_CURLY') {
    a2.bothE('NEXT').each{ g.removeEdge(it); }
    a2.rank = rank;
    ++rank;
    g.addEdge(classe, a2, '$link');
    
    toDelete.push(a3); // drop ,
    a2 = a3.out('NEXT').next();
    a3 = a2.out('NEXT').next();
}

a2.bothE('NEXT').each{ g.removeEdge(it); }
a2.rank = rank;
g.addEdge(classe, a2, '$link');

g.addEdge(it, a3, 'NEXT');

a1.bothE.each{ g.removeEdge(it); }
toDelete.push(a1); // drop IMPLEMENT

";
            unset($actions['toImplements']);
        }
        
        if (isset($actions['makeBlock'])) {
            if (is_string($actions['makeBlock'])) {
                $link = $actions['makeBlock'];
                $qactions[] = " /* makeBlock */
it.out('$link').each{
    it.setProperty('block', true);
    if (it.bracket == false) {
        it.setProperty('fullcode', ' /**/ ');
    } else {
        it.setProperty('fullcode', '{ /**/ } ');
    }
}
            ";
            } else {
                foreach($actions['makeBlock'] as $link) {
                    $qactions[] = " /* makeBlock $link */
it.out('$link').each{
    it.setProperty('block', true);
    if (it.bracket == false) {
        it.setProperty('fullcode', ' /**/ ');
    } else {
        it.setProperty('fullcode', '{ /**/ } ');
    }
}
            ";
                }
            }
            unset($actions['makeBlock']);
        }

        if (isset($actions['createSequenceForCaseWithoutSemicolon'])) {
            $sequence = new Sequence($this->gremlin);
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
    
    toDelete.push(a);
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
    
    toDelete.push(b);
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

// Get the previous token
subname = p.in('NEXT').next();
if (subname.getProperty('token') in ['T_STRING', 'T_NAMESPACE']) {
    g.addEdge(nsname, subname, 'SUBNAME');
    subname.setProperty('rank', rank++);
    subname.has('token', 'T_NAMESPACE').each{ it.setProperty('fullcode', it.code); }
    
    p2 = subname.in('NEXT').next();
    subname.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
    g.addEdge(p2, nsname, 'NEXT');
    p.setProperty('absolutens', false);
    
} else {
    rank = 0;
    p.setProperty('absolutens', true);
}

// Get the next token
while(p.getProperty('token') == 'T_NS_SEPARATOR' && p.out('NEXT').next().token != 'T_OPEN_CURLY') {
    subname = p.out('NEXT').next();
    g.addEdge(nsname, subname, 'SUBNAME');
    subname.setProperty('rank', rank++);

    p2 = subname.out('NEXT').next();
    if (p != it) {
        p.bothE('NEXT').each{ g.removeEdge(it); }
        p.bothE('INDEXED').each{ g.removeEdge(it); }
        toDelete.push(p);
    }

    p = p2;
    subname.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
}

g.addEdge(nsname, p2, 'NEXT');

";
            unset($actions['makeNamespace']);
        }
        
        if (isset($actions['createSequenceForDefaultWithoutSemicolon'])) {
            $sequence = new Sequence($this->gremlin);
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
    
    toDelete.push(a);
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
    
    toDelete.push(b);
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

        if (isset($actions['makeGroupedUse'])) {
            $qactions[] = <<<GREMLIN
/* makeGroupedUse */

it.groupedUse = true;

if (a1.token in ['T_FUNCTION', 'T_CONST']) {
    it.groupedPrefix = a1.code.toLowerCase();
    link = a1.code.toUpperCase();

    toDelete.push(a1);
    a1 = a1.out('NEXT').next();
    a2 = a1.out('NEXT').next();
} else {
    link = 'USE';
}

if (a1.atom == 'Nsname') {
    a1.out('SUBNAME').each{ toDelete.push(it); }
    it.groupPath = '\\\\' + a1.fullcode + '\\\\';
    it.fullnsprefix = '\\\\' + a1.fullcode.toLowerCase() + '\\\\';
} else {
    it.groupPath = '\\\\' + a1.fullcode + '\\\\';
    it.fullnsprefix = '\\\\' + a1.fullcode.toLowerCase() + '\\\\';

}
toDelete.push(a1);
// Drop the \
if (a2.token == 'T_NS_SEPARATOR') {
    // a1 is now unused
    toDelete.push(a2);
    a2 = a2.out('NEXT').next();
}

rank = 0;
// a2 is the { then ,
while(!(a2.token == 'T_CLOSE_CURLY')) {
    toDelete.push(a2);

    a2 = a2.out('NEXT').next();
    
    if (a2.token == 'T_FUNCTION') {
        f = a2;
        
        a2 = a2.out('NEXT').next();

        toDelete.push(f);
        
        g.addEdge(it, a2, 'FUNCTION');
    } else if (a2.token == 'T_CONST') {
        f = a2;
        
        a2 = a2.out('NEXT').next();

        toDelete.push(f);
        
        g.addEdge(it, a2, 'CONST');
    } else {
        g.addEdge(it, a2, link);
    }
    
    a2.rank = rank;
    ++rank;

    a2 = a2.out('NEXT').next();
}

g.addEdge(it, a2.out('NEXT').next(), 'NEXT');
toDelete.push(a2);

it.out('USE').bothE('NEXT').each{ g.removeEdge(it); }
it.out('CONST').bothE('NEXT').each{ g.removeEdge(it); }
it.out('FUNCTION').bothE('NEXT').each{ g.removeEdge(it); }
it.out('USE').bothE('INDEXED').each{ g.removeEdge(it); }
it.out('CONST').bothE('INDEXED').each{ g.removeEdge(it); }
it.out('FUNCTION').bothE('INDEXED').each{ g.removeEdge(it); }

GREMLIN;
            unset($actions['makeGroupedUse']);
        }
        
    if (isset($actions['insertVoid'])) {
        $out = str_repeat(".out('NEXT')", $actions['insertVoid']);
    
        $qactions[] = "
/* insertVoid */

x = g.addVertex(null, [code:'void', fullcode:' ', atom:'Void', token:'T_VOID', virtual:true, line:it.line, line:it.line]);
g.idx('atoms').put('atom', 'Void', x);

e = it{$out}.next();
f = e.out('NEXT').next();

g.removeEdge(e.outE('NEXT').next());
g.addEdge(e, x, 'NEXT');
g.addEdge(x, f, 'NEXT');

";
            unset($actions['insertVoid']);
        }

    if (isset($actions['insertCurlyVoid'])) {
        $out = str_repeat(".out('NEXT')", $actions['insertCurlyVoid']);
    
        $qactions[] = "
/* insertCurlyVoid */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
theVoid = g.addVertex(null, [code:'void', fullcode:' ', atom:'Void', token:'T_VOID', virtual:true, line:it.line, line:it.line]);
g.idx('atoms').put('atom', 'Void', theVoid);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);


e = it{$out}.next();
f = e.out('NEXT').next();

g.removeEdge(e.outE('NEXT').next());
g.addEdge(e, oc, 'NEXT');
g.addEdge(oc, theVoid, 'NEXT');
g.addEdge(theVoid, cc, 'NEXT');
g.addEdge(cc, f, 'NEXT');

";
            unset($actions['insertCurlyVoid']);
        }

        if (isset($actions['toBlock'])) {
            $qactions[] = "
/* toBlock */

a3 = a2.out('NEXT').next();
toBlockSequence = g.addVertex(null, [code:';', fullcode:'{ /**/ }', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line]);

a1.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(toBlockSequence, a1, 'ELEMENT');
a1.setProperty('rank', 0);
if (a1.atom == 'Sequence') {
    a1.block    = true;
    a1.bracket  = true;
    a1.fullcode = '{ /**/ }';
}

g.addEdge(b1, toBlockSequence, 'NEXT');
g.addEdge(toBlockSequence, a3, 'NEXT');

a1 = toBlockSequence;

it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
a2.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }

toDelete.push(it);
toDelete.push(a2);

";
            unset($actions['toBlock']);
        }

        if (isset($actions['toBlockFor']) && $actions['toBlockFor']) {
            $sequence = new Block($this->gremlin);
            $fullcode = $sequence->fullcode();

            $qactions[] = "
/* toBlockFor */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

sequence = g.addVertex(null, [code:';', fullcode:' /**/ ', token:'T_SEMICOLON', atom:'Sequence', block:true, bracket:false, virtual:true, line:it.line]);
g.addEdge(sequence, a1, 'ELEMENT');
a1.rank = 0;
a1.bothE('NEXT').each{ g.removeEdge(it); }

// remove a2, if this is a ;
if (a2.getProperty('token') == 'T_SEMICOLON' &&
    a2.getProperty('atom') == null) {
    semicolon = a2;
    a2 = semicolon.out('NEXT').next();
    
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(semicolon);
}

g.addEdge(it, oc, 'NEXT');
g.addEdge(oc, sequence, 'NEXT');

g.addEdge(sequence, cc, 'NEXT');
g.addEdge(cc, a2, 'NEXT');

            ";
            unset($actions['toBlockFor']);
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
        
        if (isset($actions['toSpecialmethodcall'])) {
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
            unset($actions['toSpecialmethodcall']);
        }
        
        if (isset($actions['insertNs'])) {
            $qactions[] = "
/* insert namespace */

it.setProperty('no_block', true);

g.addEdge(it, a1, 'NAMESPACE');
toDelete.push(a2);
g.addEdge(it, a3, 'BLOCK');

a3.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }
a1.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it) }

g.addEdge(it, a4, 'NEXT');

";
            unset($actions['insertNs']);
        }
        
        if (isset($actions['globalNamespace'])) {
            $qactions[] = '
/* insert void for namespace */


';
            unset($actions['globalNamespace']);
        }

        if (isset($actions['insertNsSeq'])) {
            $qactions[] = "
/* insert sequence for namespace */

sequence = g.addVertex(null, [code:'Sequence', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line, fullcode:';']);
g.idx('atoms').put('atom', 'Sequence', sequence);

g.addEdge(a2, sequence, 'NEXT');

g.addEdge(sequence, a3, 'ELEMENT');
a3.setProperty('rank', 0);
a3.bothE('NEXT').each{ g.removeEdge(it); }
if (a4.token == 'T_SEMICOLON') {
    a5 = a4.out('NEXT').next();
    a4.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(a4);
    g.addEdge(sequence, a5, 'NEXT');
} else {
    g.addEdge(sequence, a4, 'NEXT');
}

";
            unset($actions['insertNsSeq']);
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
toDelete.push(it.out('NEXT').next());
it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, nextnext, 'NEXT');

";
            unset($actions['sign']);
        }

        if (isset($actions['toCatch'])) {
            $fullcode = $this->fullcode();

            $qactions[] = "
/* toCatch or to_finally */
thecatch = it.out('NEXT').next();
next = thecatch.out('NEXT').next();

thecatch.setProperty('rank', it.out('CATCH').count());
g.addEdge(it, thecatch, 'CATCH');
g.addEdge(it, next, 'NEXT');
thecatch.bothE('NEXT').each{ g.removeEdge(it); }

fullcode = it;
$fullcode

";
            unset($actions['toCatch']);
        }

        if (isset($actions['toTypehint'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "

/* to type hint */

first = it;
a0 = it;
a2 = a1.out('NEXT').next();
a3 = a2.out('NEXT').next();

while (a0.token in ['T_OPEN_PARENTHESIS', 'T_COMMA']) {
    if (a1.atom in ['Typehint', 'Variable', 'Assignation', 'Void']) {
        if (a2.token == 'T_EQUAL') {
            // Skip it
            a0 = a3.out('NEXT').out('NEXT').next();
        } else {
            // Skip along, it's already done
            a0 = a2;
        }
    } else if (a1.token in ['T_STRING', 'T_NS_SEPARATOR', 'T_CALLABLE', 'T_ARRAY'] &&
               a2.atom  in ['Variable', 'Assignation']) {
        
        if (a3.token == 'T_EQUAL') {
            a0 = a3.out('NEXT').out('NEXT').next();
        } else if (a3.token in ['T_COMMA' ,'T_CLOSE_PARENTHESIS']) {
            x = g.addVertex(null, [code:'Typehint', atom:'Typehint', token:'T_TYPEHINT', virtual:true, line:it.line]);

            g.addEdge(x, a1, 'CLASS');
            if (a1.token == 'T_ARRAY') {
                a1.setProperty('atom', 'Identifier');
                a1.setProperty('fullcode', a1.code);
            }
            g.addEdge(x, a2, 'VARIABLE');
            
            a1.bothE('NEXT').each{ g.removeEdge(it);}
            a1.bothE('INDEXED').each{ g.removeEdge(it);}
            a2.bothE('NEXT').each{ g.removeEdge(it);}
            a2.bothE('INDEXED').each{ g.removeEdge(it);}

            g.addEdge(a0, x, 'NEXT');
            g.addEdge(x, a3, 'NEXT');
    
            // indexing
            g.idx('atoms').put('atom', 'Typehint', x);
    
            fullcode = x;
            $fullcode;
            
            a0 = a3;
        } else {
            Dunno;
        }
    } else {
        // In case we don't know, just skip it
       a0 = a2;
    }

    a1 = a0.out('NEXT').next();
    a2 = a1.out('NEXT').next();
    a3 = a2.out('NEXT').next();
}

if (a0.token == 'T_CLOSE_PARENTHESIS') {
//    first.inE('INDEXED').each{ g.removeEdge(it); }
}

";
            $this->setAtom = true;
            unset($actions['toTypehint']);
        }
        
        if (isset($actions['fullcode'])) {
            $this->setAtom = true;
            unset($actions['fullcode']);
        }
        
        if (isset($actions['insertVertex'])) {
            $fullcode = $this->fullcode();
        
            $qactions[] = <<<GREMLIN
/* insertVertex out */
x = g.addVertex(null, [code:"", atom:"Arguments", token:"T_COMMA", virtual:true, line:it.line]);
g.idx("atoms").put("atom", "Arguments", x);

a2 = a1.out("NEXT").next();

g.addEdge(it, x, "NEXT");
g.addEdge(x, a2, "NEXT");
g.addEdge(x, a1, "ARGUMENT");
x.setProperty("fullcode", a1.fullcode);
a1.bothE("NEXT").each{g.removeEdge(it);}

a1.inE("INDEXED").each{ g.removeEdge(it); }

fullcode = x;
$fullcode
GREMLIN;
            unset($actions['insertVertex']);
        }

        if (isset($actions['makeArguments'])) {
                $qactions[] = <<<GREMLIN
/* makeArgument */

x = g.addVertex(null, [code:',', atom:'Arguments', fullcode:a1.fullcode, token:'T_COMMA', virtual:true, line:it.line]);
g.idx('atoms').put('atom', 'Arguments', x);

g.addEdge(x, a1, 'ARGUMENT');
a1.rank = 0;
a1.bothE('NEXT').each{g.removeEdge(it);}
a1.inE('INDEXED').each{ g.removeEdge(it); }

g.addEdge(it, x, 'NEXT');
g.addEdge(x, a2, 'NEXT');

fullcode = x;
GREMLIN;
            unset($actions['makeArguments']);
        }

        if (isset($actions['addEdge'])) {
            foreach($actions['addEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                $qactions[] = "
/* addEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
g.idx('atoms').put('atom', 'Void', x);

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
g.idx('atoms').put('atom', 'Void', x);

f = it.in('NEXT').next();

g.removeEdge(it.inE('NEXT').next());
g.addEdge(x, it, 'NEXT');
g.addEdge(f, x, 'NEXT');

";
            } elseif ($destination > 0) {
                list($atom, $link) = each($config);
                if ($destination == 1) {
                    $next = 'it;';
                } else {
                    $next = 'it'.str_repeat(".out('NEXT')", $destination - 1).'.next()';
                }
                
                $qactions[] = "
/* addEdge out $destination */
x = g.addVertex(null, [code:'void', token:'T_VOID', atom:'$atom', virtual:true, line:it.line, fullcode:' ']);
g.idx('atoms').put('atom', 'Void', x);

a = $next;
b = a.out('NEXT').next();

g.removeEdge(a.outE('NEXT').next());
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

";
            } else {
                echo "Only support for addEdge with destination -1 or 0\n";
            }
            unset($actions['addEdge']);
            }
        }

        if (isset($actions['toConcatenation']) && $actions['toConcatenation']) {
                $qactions[] = "
/* to Concatenation */

x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
g.idx('atoms').put('atom', 'Concatenation', x)

// initial
rank = 0;
g.addEdge(x, b1, 'CONCAT');
if (b1.in_quote != null) {
    x.setProperty('in_quote', true);
}
b1.setProperty('rank', rank);
b1.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(b2, x, 'NEXT');

while(a2.token == 'T_DOT') {
    g.addEdge(x, a1, 'CONCAT');
    rank += 1;
    a1.setProperty('rank', rank);
    a2.inE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(a2);

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
toDelete.push(it);
g.addEdge(x, a2, 'NEXT');

x.out('CONCAT').inE('INDEXED').each{ g.removeEdge(it); }

fullcode = x;

";
            unset($actions['toConcatenation']);
        }

        if (isset($actions['toArray']) && $actions['toArray']) {
            $array = new _Array($this->gremlin);
            $fullcodeArray = $array->fullcode();

            $arrayAppend = new Arrayappend($this->gremlin);
            $fullcodeArrayappend = $arrayAppend->fullcode();

            $qactions[] = "
/* to Array */

current = it;
previous = b1.in('NEXT').next();

while( current.atom == null && current.token in ['T_OPEN_BRACKET', 'T_OPEN_CURLY']) {
    if (a1.token == 'T_CLOSE_BRACKET') {
        /* case of a arrayappend (\$s[]) */
        g.addEdge(current, b1, 'VARIABLE');
        current.setProperty('atom', 'Arrayappend');

        current.inE('INDEXED').each{ g.removeEdge(it); }
        fullcode = current;
        $fullcodeArrayappend;

        b1.bothE('NEXT').each{ g.removeEdge(it); }
        a1.inE('NEXT').each{ g.removeEdge(it); }

        b1 = current;
        current = a1.out('NEXT').next();
        g.removeVertex(a1);

        a1 = current.out('NEXT').next();
        a2 = a1.out('NEXT').next();
    
        g.idx('atoms').put('atom', 'Arrayappend', current);
    } else { //a2.token == 'T_CLOSE_BRACKET'
        /* case of a array with index (\$s[1]) */
        a3 = a2.out('NEXT').next();
        g.addEdge(current, b1, 'VARIABLE');
        if (a1.atom == 'Sequence') {
            if (a1.out('ELEMENT').count() != 1) {
                WrongNumberOfElementInAnArrayIndex;
            } else {
                a11 = a1.out('ELEMENT').next();
                a11.inE('ELEMENT').each{ g.removeEdge(it); }
                g.addEdge(current, a11, 'INDEX');
                
                g.removeVertex(a1);
            }
        } else {
            g.addEdge(current, a1, 'INDEX');
            a1.inE('INDEXED').each{ g.removeEdge(it); }

            a1.bothE('NEXT').each{ g.removeEdge(it); }
        }
        
        current.setProperty('atom', 'Array');
        g.idx('atoms').put('atom', 'Array', current)
        
        current.inE('INDEXED').each{ g.removeEdge(it); }
        fullcode = current;
        $fullcodeArray;

        b1.bothE('NEXT').each{ g.removeEdge(it); }
        current.bothE('NEXT').each{ g.removeEdge(it); }

        b1 = current;
        current = a3;

        a2.bothE('NEXT').each{ g.removeEdge(it); }
        g.removeVertex(a2);

        a1 = current.out('NEXT').next();
        a2 = a1.out('NEXT').next();
    }
}

g.addEdge(previous, b1, 'NEXT');
g.addEdge(b1, current, 'NEXT');

if (current.token == 'T_OPEN_PARENTHESIS') {
    g.addEdge(g.idx('racines')[['token':'S_ARRAY']].next(), b1, 'INDEXED');
}

";
            unset($actions['toArray']);
        }

        if (isset($actions['toSequence']) && $actions['toSequence']) {
            $endSequence = "'T_CLOSE_TAG', 'T_DEFAULT', 'T_CASE', 'T_ENDIF', 'T_ENDFOR', 'T_ENDFOREACH', 'T_ENDWHILE',
                            'T_ENDDECLARE', 'T_SEQUENCE_CASEDEFAULT', 'T_END', 'T_CLOSE_CURLY', 'T_ELSEIF', 'T_ELSE' ";

            $qactions[] = "
/* to Sequence */

// those may be unavailable, depending on the rule.
b1 = it.in('NEXT').next();
a1 = it.out('NEXT').next();

if (it.atom == 'Sequence' && it.bracket == null) {
    current = it;
    rank = it.out('ELEMENT').count();
    
    a2 = a1.out('NEXT').next();
} else if (b1.atom == 'Sequence' && b1.bracket == null) {
    current = b1;
    rank = b1.out('ELEMENT').count() - 1;

    a2 = a1.out('NEXT').next();
} else {
    current = it;
    // This is is going to be a new sequence
    g.idx('atoms').put('atom', 'Sequence', current);
    current.setProperty('atom', 'Sequence');
    current.setProperty('fullcode', ';');

    b2 = b1.in('NEXT').next();
    b1.setProperty('rank', 0);
    b1.bothE('NEXT').each{
        g.removeEdge(it);
    }
    g.addEdge(current, b1, 'ELEMENT');
    rank = 1;

    a2 = a1.out('NEXT').next();

    g.addEdge(b2, current, 'NEXT');
}
makeNext = false;

// LOOPS
while( !(a1.token in ['T_SEQUENCE_CASEDEFAULT', 'T_ELSEIF']) &&
        (a1.atom != null) &&
        ( (a1.atom == 'Sequence' && a1.bracket == null) ||
          (a2.token in ['T_SEMICOLON', $endSequence]))) {

     if (a1.atom == 'Sequence' && a1.bracket == null) {
        a1.out('ELEMENT').each{
            g.addEdge(current, it, 'ELEMENT');
            it.setProperty('rank', it.getProperty('rank') + rank);
        }
        rank = current.out('ELEMENT').count();

        a1.bothE().each{ g.removeEdge(it); };
        toDelete.push(a1);
        a1 = a2;
        a2 = a1.out('NEXT').next();
        makeNext = true;
    } else if (a1.atom != null && a2.token == 'T_SEMICOLON' && a2.atom == null) {
        if (a1.atom == 'Sequence') {
            a1.out('ELEMENT').each{
                g.addEdge(current, it, 'ELEMENT');
                it.setProperty('rank', it.getProperty('rank') + rank);
            }
            rank = current.out('ELEMENT').count();
    
            a1.bothE('ELEMENT', 'INDEXED', 'NEXT').each{ g.removeEdge(it); };
            toDelete.push(a1);
        } else {
            a1.setProperty('rank', rank);
            rank = rank + 1;
            a1.bothE('NEXT').each{ g.removeEdge(it); }
            g.addEdge(current, a1, 'ELEMENT');
        }

        a1 = a2.out('NEXT').next();

        a2.bothE('INDEXED', 'NEXT').each{ g.removeEdge(it); };
        toDelete.push(a2);
        a2 = a1.out('NEXT').next();
        makeNext = true;
    } else if (a1.atom != null && a2.token == 'T_SEMICOLON' && a2.atom == 'Sequence') {
        if (a1.atom == 'Sequence') {
            MergingTwoSequences; // shouldn't happen
        } else {
            a1.setProperty('rank', rank);
            rank = rank + 1;
            a1.bothE('NEXT').each{ g.removeEdge(it); }
            g.addEdge(current, a1, 'ELEMENT');

            a2.out('ELEMENT').each{
                it.rank += rank;
                it.inE('ELEMENT').each{ g.removeEdge(it); }
                g.addEdge(current, it, 'ELEMENT');
            }

            rank = current.out('ELEMENT').count();
            
            a1 = a2.out('NEXT').next();
            a2.bothE('INDEXED', 'NEXT').each{ g.removeEdge(it); };
            toDelete.push(a2);

            a2 = a1.out('NEXT').next();
        }
        makeNext = true;
    } else if (a1.atom != null && a2.token in [$endSequence]) {
        if (a1.atom == 'Sequence') {
            a1.out('ELEMENT').each{
                g.addEdge(current, it, 'ELEMENT');
                it.setProperty('rank', it.getProperty('rank') + rank + 1);
            }
            rank = current.out('ELEMENT').count();
    
            a1.bothE('ELEMENT', 'INDEXED', 'NEXT').each{ g.removeEdge(it); };
            toDelete.push(a1);
        } else {
            a1.setProperty('rank', rank);
            rank = rank + 1;
            a1.bothE('NEXT').each{ g.removeEdge(it); }
            g.addEdge(current, a1, 'ELEMENT');
        }

        a1 = a2;
        a2 = a1.out('NEXT').next();
        makeNext = true;
    } else {
        // Undefined variables, that acts as a die.
        UnprocessedSequence;
    }
}

// LOOPS

// FINISH

if (makeNext == true) {
    // clean outgoing link first
    current.out('NEXT').each{
        it.inE('NEXT').each{  g.removeEdge(it); }
        toDelete.push(it);
    }
    g.addEdge(current, a1, 'NEXT');
}

current.out('ELEMENT').inE('INDEXED').each{ g.removeEdge(it); };

current.out('ELEMENT').has('root', true).each{
    file = it.in('FILE').next();

    current.setProperty('root', true);
    g.addEdge(file, current, 'FILE');

    it.removeProperty('root');
    g.removeEdge(it.inE('FILE').next());
};

";
            unset($actions['toSequence']);
        }

        if (isset($actions['toOneSequence']) && $actions['toOneSequence']) {
            $qactions[] = "
/* to toOneSequence */

b2 = b1.in('NEXT').next();

b1.setProperty('rank', 0);
b1.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, b1, 'ELEMENT');

b1.inE('INDEXED').each{ g.removeEdge(it); }
it.setProperty('atom', 'Sequence');
it.setProperty('count', 1);
g.idx('atoms').put('atom','Sequence', it);
it.setProperty('fullcode', ';'); // fullcode

g.addEdge(b2, it, 'NEXT');

";
            unset($actions['toOneSequence']);
        }

        if (isset($actions['toArgument']) && $actions['toArgument']) {
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

    // prepare next round
    a3 = a2.out('NEXT').next();
    a4 = a3.out('NEXT').next();

    a1.bothE('NEXT').each{ g.removeEdge(it); }
    a2.bothE('NEXT').each{ g.removeEdge(it); }
    toDelete.push(a2);

    a1 = a3;
    a2 = a4;
}

g.addEdge(x, a1, 'ARGUMENT');
rank = rank + 1;
a1.setProperty('rank', rank);
a1.bothE('NEXT').each{ g.removeEdge(it); }

a2.inE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(it);

g.addEdge(x, a2, 'NEXT');

x.out('ARGUMENT').inE('INDEXED').each{ g.removeEdge(it);}

fullcode = x;

";
            unset($actions['toArgument']);
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

        if (isset($actions['toBlockElse']) && $actions['toBlockElse']) {
            $sequence = new Sequence($this->gremlin);
            $fullcode = $sequence->fullcode();
            
            $offset = str_repeat(".out('NEXT')", $actions['toBlockElse']);

            $qactions[] = "
/* toBlockElse */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

x = g.addVertex(null, [code:'Block with else', fullcode:' /**/ ', token:'T_SEMICOLON', atom:'Sequence', block:true, bracket:false, virtual:true, line:it.line]);

// remove the next, if this is a ;
if (a3.getProperty('token') == 'T_SEMICOLON' &&
    a3.getProperty('atom') == null) {
    semicolon = a3;
    a3 = semicolon.out('NEXT').next();
    
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(semicolon);
}

g.addEdge(a1, oc, 'NEXT');
g.addEdge(oc, x, 'NEXT');

g.addEdge(x, cc, 'NEXT');
g.addEdge(cc, a3, 'NEXT');

g.addEdge(x, a2, 'ELEMENT');
a2.setProperty('rank', 0);
a2.bothE('NEXT').each{ g.removeEdge(it); }

/* Clean index */
x.out('ELEMENT').each{
    it.inE('INDEXED').each{
        g.removeEdge(it);
    }
}

";
            unset($actions['toBlockElse']);
        }
        
        if (isset($actions['toBlockForeach']) && $actions['toBlockForeach']) {
            // == toBlockIfelseif - ; removal
            $offset = str_repeat(".out('NEXT')", $actions['toBlockForeach']);
            $qactions[] = "
/* toBlockForeach */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

sequence = g.addVertex(null, [code:';', fullcode:';', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, block:true, bracket:false, fullcode:' /**/ ']);

instruction = it$offset.next();
binstruction = instruction.in('NEXT').next();
ainstruction = instruction.out('NEXT').next();

// remove the next, if this is a ;
if (ainstruction.getProperty('token') == 'T_SEMICOLON' &&
    ainstruction.getProperty('atom') == null) {
    semicolon = ainstruction;
    ainstruction = semicolon.out('NEXT').next();
    
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(semicolon);
}

instruction.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(sequence, instruction, 'ELEMENT');
instruction.setProperty('rank', 0);

g.addEdge(binstruction, oc, 'NEXT');
g.addEdge(oc, sequence, 'NEXT');

g.addEdge(sequence, cc, 'NEXT');
g.addEdge(cc, ainstruction, 'NEXT');

            ";
            unset($actions['toBlockForeach']);
        }
        
        if (isset($actions['toBlockIfelseif']) && $actions['toBlockIfelseif']) {
            $offset = str_repeat(".out('NEXT')", $actions['toBlockIfelseif']);
            $qactions[] = "
/* toBlockIfelseif ({$actions['toBlockIfelseif']})*/

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

sequence = g.addVertex(null, [code:';', fullcode:';', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, block:true, bracket:false, fullcode:' /**/ ']);

instruction = it$offset.next();
binstruction = instruction.in('NEXT').next();  // before instruction
ainstruction = instruction.out('NEXT').next(); // after instruction

instruction.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ;
if (ainstruction.getProperty('token') == 'T_SEMICOLON' &&
    ainstruction.getProperty('atom') == null) {
    semicolon = ainstruction;
    ainstruction = semicolon.out('NEXT').next();
    
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(semicolon);
}

g.addEdge(sequence, instruction, 'ELEMENT');
instruction.bothE('INDEXED').each{ g.removeEdge(it); }
instruction.setProperty('rank', 0);

g.addEdge(binstruction, oc, 'NEXT');
g.addEdge(oc, sequence, 'NEXT');

g.addEdge(sequence, cc, 'NEXT');
g.addEdge(cc, ainstruction, 'NEXT');

";
            unset($actions['toBlockIfelseif']);
        }

        if (isset($actions['toBlockIfelseifAlternative']) && $actions['toBlockIfelseifAlternative']) {
            $offset = str_repeat(".out('NEXT')", $actions['toBlockIfelseifAlternative']);
            $qactions[] = "
/* toBlockIfelseifAlternative ({$actions['toBlockIfelseifAlternative']})*/

sequence = g.addVertex(null, [code:';', fullcode:';', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, block:true, fullcode:' /**/ ']);

instruction = it$offset.next();

binstruction = instruction.in('NEXT').next();
ainstruction = instruction.out('NEXT').next();
instruction.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ;
if (ainstruction.getProperty('token') == 'T_SEMICOLON') {
    semicolon = ainstruction;
    ainstruction = semicolon.out('NEXT').next();
    
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    semicolon.bothE('INDEXED').each{ g.removeEdge(it); }
    toDelete.push(semicolon);
}

g.addEdge(sequence, instruction, 'ELEMENT');
instruction.setProperty('rank', 0);

g.addEdge(binstruction, sequence, 'NEXT');

g.addEdge(sequence, ainstruction, 'NEXT');

";
            unset($actions['toBlockIfelseifAlternative']);
        }

        if (isset($actions['toBlockIfelseifInstruction']) && $actions['toBlockIfelseifInstruction']) {
                $qactions[] = "
/* toBlockIfelseifInstruction */

x = g.addVertex(null, [code:'Block with control if elseif', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, fullcode:' /**/ ', block:true ]);
a = it.out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'ELEMENT');
a.setProperty('rank', 0);
a.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['toBlockIfelseifInstruction']);
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
            $sequence = new Sequence($this->gremlin);
            $fullcode = $sequence->fullcode();

            $qactions[] = "
/* createBlockWithSequenceForCase */
a = a3;

// if the expression is followed by a ;, remove it.
a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    it.bothE('INDEXED').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

if (a.atom != 'Sequence') {
    x = g.addVertex(null, [code:'Block with Sequence For Case', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line]);
    fullcode = x;
    $fullcode

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
}

            ";
            unset($actions['createBlockWithSequenceForCase']);
        }

        if (isset($actions['createBlockWithSequenceForDefault']) && $actions['createBlockWithSequenceForDefault']) {
            $sequence = new Sequence($this->gremlin);
            $fullcode = $sequence->fullcode();

            $qactions[] = "
/* createBlockWithSequenceForDefault */
a = a2;

a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    it.bothE('INDEXED').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

if (a.atom != 'Sequence') {
    x = g.addVertex(null, [code:'Block with Sequence For Default', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
    fullcode = x;
    $fullcode

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
}
            ";
            unset($actions['createBlockWithSequenceForDefault']);
        }

        if (isset($actions['createVoidForCase']) && $actions['createVoidForCase']) {
            $qactions[] = "
/* createVoidForCase */
x = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line, fullcode:' ']);
g.idx('atoms').put('atom', 'Void', x);
a = it.out('NEXT').out('NEXT').next();
b = a.out('NEXT').next();

a.outE('NEXT').each{ g.removeEdge(it) ; }
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

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
g.idx('atoms').put('atom', 'Void', x);
a = it.out('NEXT').next();
b = a.out('NEXT').next();

a.outE('NEXT').each{ g.removeEdge(it) ; }
g.addEdge(a, x, 'NEXT');
g.addEdge(x, b, 'NEXT');

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
        
        toDelete.push(cds2);
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

        if (isset($actions['toVariable'])) {
            $qactions[] = "
/* to variable */
variable = it.out('NEXT').next();
variable.setProperty('delimiter', it.code);
variable.setProperty('enclosing', it.token);

if (it.token == 'T_CURLY_OPEN') {
    variable.setProperty('inBracket', true);
} else if (it.token == 'T_DOLLAR_OPEN_CURLY_BRACES') {
    variable.setProperty('inBracketDollar', true);
}

toDelete.push(it);
g.addEdge(it.in('NEXT').next(), variable, 'NEXT');
it.bothE('NEXT').each{ g.removeEdge(it); }

toDelete.push(variable.out('NEXT').next());
close_curly = variable.out('NEXT').next();
g.addEdge(variable, variable.out('NEXT').out('NEXT').next(), 'NEXT');
close_curly.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['toVariable']);
        }

        if (isset($actions['toVariableDollar'])) {
            $qactions[] = "
/* to variable dollar */

a4 = a3.out('NEXT').next();
g.addEdge(it, a4, 'NEXT');

a1.bothE('NEXT').each{ g.removeEdge(it); }
a2.bothE('NEXT').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }

if (a2.atom == 'Sequence') {
    g.addEdge(it, a2.out('ELEMENT').next(), 'NAME');
    g.removeVertex(a2);
} else {
    g.addEdge(it, a2, 'NAME');
}

g.removeVertex(a1);
g.removeVertex(a3);

if (a4.token == 'T_OPEN_PARENTHESIS') {
    g.addEdge(g.idx('racines')[['token':'S_ARRAY']].next(), it, 'INDEXED');
}

";
            unset($actions['toVariableDollar']);
        }
        
        if (isset($actions['makeForeachSequence'])) {
            $qactions[] = "
/* make Foreach Sequence */
block = g.addVertex(null, [code:'Block with Foreach', token:'T_SEMICOLON', atom:'Sequence', virtual:true, line:it.line, fullcode:'{ /**/ } ']);
element1 = a2;
element2 = a3;

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

        if (isset($actions['toDowhileBlock'])) {
            $qactions[] = "
/* toDowhileBlock */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

sequence = g.addVertex(null, [code:';', token:'T_SEMICOLON', atom:'Sequence', virtual:true, block:true, bracket:false, line:it.line, fullcode:' /**/ ']);
g.addEdge(sequence, a1, 'ELEMENT');
a1.rank = 0;

a1.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(it, oc, 'NEXT');
g.addEdge(oc, sequence, 'NEXT');

if (a2.token == 'T_SEMICOLON') {
    a2.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(a2);
    
    g.addEdge(sequence, cc, 'NEXT');
    g.addEdge(cc, a3, 'NEXT');
} else {
    g.addEdge(sequence, cc, 'NEXT');
    g.addEdge(cc, a2, 'NEXT');
}


";
            unset($actions['toDowhileBlock']);
        }
        
        if (isset($actions['toWhileBlock'])) {
            $qactions[] = "
/* toWhileBlock */

oc = g.addVertex(null, [code:'{', token:'T_OPEN_CURLY', virtual:true, line:it.line]);
cc = g.addVertex(null, [code:'}', token:'T_CLOSE_CURLY', virtual:true, line:it.line]);

sequence = g.addVertex(null, [code:';', token:'T_SEMICOLON', atom:'Sequence', virtual:true, block:true, bracket:false, line:it.line, fullcode:' /**/ ']);
g.addEdge(sequence, a4, 'ELEMENT');
a4.rank = 0;

a4.bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(a3, oc, 'NEXT');
g.addEdge(oc, sequence, 'NEXT');
if (a5.token == 'T_SEMICOLON') {
    a6 = a5.out('NEXT').next();
    
    a5.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(a5);
    
    g.addEdge(sequence, cc, 'NEXT');
    g.addEdge(cc, a6, 'NEXT');
} else {
    g.addEdge(a3, oc, 'NEXT');
    g.addEdge(oc, sequence, 'NEXT');

    g.addEdge(sequence, cc, 'NEXT');
    g.addEdge(cc, a5, 'NEXT');
}


";
            unset($actions['toWhileBlock']);
        }

        if (isset($actions['to_methodcall'])) {
            $string = new Methodcall($this->gremlin);
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

        if (isset($actions['checkTypehint'])) {
            $reference = new Reference($this->gremlin);
            $fullcodeReference = $reference->fullcode();

            $typehint = new Typehint($this->gremlin);
            $fullcodeTypehint = $typehint->fullcode();

            $arguments = new Arguments($this->gremlin);
            $fullcodeArguments = $arguments->fullcode();

            $qactions[] = <<<GREMLIN
/* Turn a & b into a typehint  */

it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Logical').each{
    // set early or it will fail
    it.setProperty('atom', 'Typehint');
    it.out('RIGHT').has('atom', 'Variable').each{
        it.setProperty('reference', true);
        fullcode = it;
        $fullcodeReference
    }

    it.out('RIGHT').out('LEFT').has('atom', 'Variable').each{
        it.setProperty('reference', true);
        fullcode = it;
        $fullcodeReference
    }

    // removed from g.idx('logical')?
    g.idx('atoms').put('atom', 'Typehint', it);
    g.idx('atoms').remove('atom', 'Logical', it);

    g.addEdge(it, it.out('LEFT').next(), 'CLASS');
    g.removeEdge(it.outE('LEFT').next());

    g.addEdge(it, it.out('RIGHT').next(), 'VARIABLE');
    g.removeEdge(it.outE('RIGHT').next());
    
    fullcode = it;
    $fullcodeTypehint
}

fullcode = it.out('ARGUMENTS').next();
$fullcodeArguments

fullcode = it;

GREMLIN;
            unset($actions['checkTypehint']);
        }

        if (isset($actions['makeQuotedString'])) {
            $atom = str_replace('_', '', $actions['makeQuotedString']);
            $class = "\\Tokenizer\\".$actions['makeQuotedString'];
            $string = new $class($this->gremlin);
            $fullCodeString = $string->fullcode();
            
            $qactions[] = "
/* makeQuotedString */

x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);

rank = 0;
it.out('NEXT').loop(1){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}
                      {!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.each{
    if (it.token in ['T_CURLY_OPEN', 'T_CLOSE_CURLY']) {
        it.inE('NEXT').each{ g.removeEdge(it);}
        toDelete.push(it);
    } else {
        g.addEdge(x, it, 'CONCAT');
        it.setProperty('rank', rank);
        rank++;
        it.inE('NEXT').each{ g.removeEdge(it); }
        f = it;
    }
}

g.addEdge(it, x, 'CONTAINS');
g.addEdge(it, f.out('NEXT').out('NEXT').next(), 'NEXT');

toDelete.push(f.out('NEXT').next());
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
            unset($actions['makeQuotedString']);
        }

        if (isset($actions['emptyHeredoc'])) {
            $heredoc = new Heredoc($this->gremlin);
            $fullcode = $heredoc->fullcode();

            $qactions[] = "
/* emptyHeredoc */

concat = g.addVertex(null, [code:'', fullcode:'', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
content = g.addVertex(null, [code:'', fullcode:'', atom:'String', token:'T_ENCAPSED_AND_WHITESPACE', virtual:true, line:it.line, rank:0, noDelimiter:'']);

g.addEdge(it, concat, 'CONTAINS');
g.addEdge(concat, content, 'CONCAT');

g.addEdge(it, a1.out('NEXT').next(), 'NEXT');
a1.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(a1);

/* indexing */  g.idx('atoms').put('atom', 'Heredoc', it);

fullcode = concat;
$fullcode;

";
            unset($actions['emptyHeredoc']);
        }

        if (isset($actions['methodToFunctioncall'])) {
            $token = new Functioncall($this->gremlin);
            $fullcode = $token->fullcode();

            $qactions[] = <<<GREMLIN
/* create a functioncall, and hold the methodcall as property.  */

// build the functioncall
b1 = it.in('NEXT').next();
it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }

a1.bothE('NEXT').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }
toDelete.push(a1);
toDelete.push(a3);

// bury it as a property name
x = g.addVertex(null, [code:it.fullcode, fullcode: it.fullcode, atom:'Functioncall', token:'T_OBJECT_OPERATOR', virtual:true, line:it.line, parenthesis:true]);
g.addEdge(g.idx('racines')[['token':'Property']].next(), x, 'INDEXED');

g.addEdge(x, a2, 'ARGUMENTS');
fullcode = x;
$fullcode

g.addEdge(b1, x, 'NEXT');
g.addEdge(x, a4, 'NEXT');
g.addEdge(x, it, 'NAME');

GREMLIN;
            unset($actions['methodToFunctioncall']);
        }

        if (isset($actions['staticmethodToFunctioncall'])) {
            $token = new Functioncall($this->gremlin);
            $fullcode = $token->fullcode();

            $qactions[] = <<<GREMLIN
/* create a functioncall, and hold the methodcall as property.  */

// build the functioncall
b1 = it.in('NEXT').next();
it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }

a1.bothE('NEXT').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }
toDelete.push(a1);
toDelete.push(a3);

// bury it as a property name
x = g.addVertex(null, [code:fullcode.fullcode, fullcode:fullcode.fullcode, atom:'Functioncall', token:'T_DOUBLE_COLON', virtual:true, line:it.line, parenthesis:true]);
g.addEdge(g.idx('racines')[['token':'Staticproperty']].next(), x, 'INDEXED');
g.addEdge(x, a2, 'ARGUMENTS');

fullcode = x;
$fullcode;

g.addEdge(b1, x, 'NEXT');
g.addEdge(x, a4, 'NEXT');
g.addEdge(x, it, 'NAME');

GREMLIN;
            unset($actions['staticmethodToFunctioncall']);
        }

        if (isset($actions['functionToFunctioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* create a functioncall, and hold the first functioncall as property.  */

// build the functioncall
g.addEdge(it, a2, 'ARGUMENTS');
b1 = it.in('NEXT').next();
it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
fullcode = it;
$fullcode

a1.bothE('NEXT').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }
toDelete.push(a1);
toDelete.push(a3);

// bury it as a property name
x = g.addVertex(null, [code:it.fullcode, fullcode: it.fullcode, atom:'Functioncall', token:'T_STRING', virtual:true, line:it.line]);
g.addEdge(g.idx('racines')[['token':'Functioncall']].next(), x, 'INDEXED');

g.addEdge(b1, x, 'NEXT');
g.addEdge(x, a4, 'NEXT');
g.addEdge(x, it, 'NAME');

";
            unset($actions['functionToFunctioncall']);
        }

        if (isset($actions['variableToFunctioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* do not create a functioncall, but hold the variable as property.  */

x = g.addVertex(null, [code:it.code, fullcode: it.code, atom:'Variable', token:'T_VARIABLE', virtual:true, line:it.line]);
g.addEdge(it, x, 'NAME');
// No need for indexing here
g.idx('atoms').put('atom', 'Variable', x);

";
            unset($actions['variableToFunctioncall']);
        }

        if (isset($actions['arrayToFunctioncall'])) {
            $fullcode = $this->fullcode();
            
            $qactions[] = "
/* hold the array as property.  */

// it may be an array or a variable variable
x = g.addVertex(null, [code:it.code, atom:'Functioncall', token:it.token, virtual:true, line:it.line]);
g.idx('atoms').put('atom', 'Functioncall', x);
g.addEdge(x, it, 'NAME');
g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a3.out('NEXT').next(), 'NEXT');
g.addEdge(x, a2, 'ARGUMENTS');

it.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
a1.bothE('NEXT', 'INDEXED').each{ g.removeEdge(it); }
a3.bothE('NEXT').each{ g.removeEdge(it); }

g.removeVertex(a1);
g.removeVertex(a3);

fullcode = x;
$fullcode

                ";
            unset($actions['arrayToFunctioncall']);
        }

        if (isset($actions['addSemicolon'])) {
            $token = $actions['addSemicolon'];
            $avoidSemicolon = "'T_SEMICOLON', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_AS', 'T_CLOSE_PARENTHESIS', 'T_COMMA', 'T_END', 'T_DOT',
                               'T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER',
                               'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                               'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                               'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR', 'T_COALESCE', 'T_SPACESHIP',
                               'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 'T_QUESTION', 'T_COLON',
                               'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_CURLY',
                               'T_AND_EQUAL', 'T_CONCAT_EQUAL', 'T_EQUAL', 'T_DIV_EQUAL', 'T_MINUS_EQUAL', 'T_MOD_EQUAL', 'T_MUL_EQUAL',
                               'T_OR_EQUAL', 'T_PLUS_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
                               'T_POW_EQUAL', 'T_DOUBLE_ARROW', 'T_SR','T_SL', 'T_IMPLEMENTS', 'T_EXTENDS',
                               'T_POW', 'T_PLUS', 'T_MINUS', 'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_INC', 'T_DEC',
                               'T_INSTANCEOF', 'T_INSTEADOF', 'T_ELSEIF', 'T_ELSE'";
//'T_OPEN_CURLY',
            $qactions[] = <<<GREMLIN
/* adds a semicolon  */

if ($token.out('NEXT').filter{ it.token in [$avoidSemicolon]}.has('atom', null).any() == false &&
    $token.in_quote == null) {
    semicolon = g.addVertex(null, [code:';', token:'T_SEMICOLON',virtual:true, line:it.line, addSemicolon:true]);

    next = $token.out('NEXT').next();

    $token.outE('NEXT').each{ g.removeEdge(it); }

    g.addEdge($token, semicolon, 'NEXT');
    g.addEdge(semicolon, next, 'NEXT');

    g.addEdge(g.idx('racines')[['token':'Sequence']].next(), semicolon, 'INDEXED');
}

GREMLIN;
            unset($actions['addSemicolon']);
        }

        if (isset($actions['addSemicolonFunction'])) {
            $token = $actions['addSemicolonFunction'];

            $avoidToken = "'T_SEMICOLON', 'T_CLOSE_BRACKET'";
            $qactions[] = <<<GREMLIN
/* always adds a semicolon to named functions,
   And not to closures. */

// If next token is not an semicolon only add a ;
if ($token.out('NAME').has('code', '').any() == false) { // Not Closure
    if ($token.out('NEXT').filter{ it.token in [$avoidToken]}.has('atom', null).any()) { // followed by ;
        semicolon = g.addVertex(null, [code:';', token:'T_SEMICOLON',virtual:true, line:it.line, addSemicolon:true]);
        tvoid     = g.addVertex(null, [code:'', fullcode:' ', token:'T_VOID', atom:'Void', virtual:true, line:it.line, addSemicolon:true]);
        g.idx('atoms').put('atom', 'Void', tvoid);

        next = $token.out('NEXT').next();

        $token.outE('NEXT').each{ g.removeEdge(it); }

        g.addEdge($token, semicolon, 'NEXT');
        g.addEdge(semicolon, tvoid, 'NEXT');
        g.addEdge(tvoid, next, 'NEXT');

        g.addEdge(g.idx('racines')[['token':'Sequence']].next(), semicolon, 'INDEXED');
    } else { // Closure (named fucntion)
        semicolon = g.addVertex(null, [code:';', token:'T_SEMICOLON',virtual:true, line:it.line, addSemicolon:true]);
    
        next = $token.out('NEXT').next();
    
        $token.outE('NEXT').each{ g.removeEdge(it); }
    
        g.addEdge($token, semicolon, 'NEXT');
        g.addEdge(semicolon, next, 'NEXT');
    
        g.addEdge(g.idx('racines')[['token':'Sequence']].next(), semicolon, 'INDEXED');
    }
}

GREMLIN;
            unset($actions['addSemicolonFunction']);
        }
        
        if (isset($actions['addAlwaysSemicolon'])) {
            $token = $actions['addAlwaysSemicolon'];
            $avoidSemicolon = "'T_SEMICOLON'";

            $qactions[] = <<<GREMLIN
/* always adds a semicolon (except rare cases) */

// If next token is not an semicolon only add a ;
if ($token.out('NEXT').has('token', 'T_CLOSE_PARENTHESIS').has('atom', null).any()) {
    // Do nothing
} else if ($token.out('NEXT').filter{ it.token in [$avoidSemicolon]}.has('atom', null).any() == false) {
    semicolon = g.addVertex(null, [code:';', token:'T_SEMICOLON',virtual:true, line:it.line, addSemicolon:true]);

    next = $token.out('NEXT').next();

    $token.outE('NEXT').each{ g.removeEdge(it); }

    g.addEdge($token, semicolon, 'NEXT');
    g.addEdge(semicolon, next, 'NEXT');

    g.addEdge(g.idx('racines')[['token':'Sequence']].next(), semicolon, 'INDEXED');
} else {
// If next token is a semicolon, add a void and ;
    semicolon = g.addVertex(null, [code:';', token:'T_SEMICOLON',virtual:true, line:it.line, addSemicolon:true]);
    tvoid     = g.addVertex(null, [code:'', fullcode:' ', token:'T_VOID', atom:'Void', virtual:true, line:it.line, addSemicolon:true]);
    g.idx('atoms').put('atom', 'Void', tvoid);

    next = $token.out('NEXT').next();

    $token.outE('NEXT').each{ g.removeEdge(it); }

    g.addEdge($token, semicolon, 'NEXT');
    g.addEdge(semicolon, tvoid, 'NEXT');
    g.addEdge(tvoid, next, 'NEXT');

    g.addEdge(g.idx('racines')[['token':'Sequence']].next(), semicolon, 'INDEXED');
}

GREMLIN;
            unset($actions['addAlwaysSemicolon']);
        }

        if (isset($actions['cleanIndex'])) {
            $qactions[] = "
/* Remove children's index */
it.out('NAME', 'PROPERTY', 'OBJECT', 'DEFINE', 'CODE', 'LEFT', 'RIGHT', 'SIGN', 'NEW', 'RETURN', 'CONSTANT', 'CLASS', 'VARIABLE',
'INDEX', 'EXTENDS', 'SUBNAME', 'POSTPLUSPLUS', 'PREPLUSPLUS', 'VALUE', 'CAST', 'SOURCE', 'USE', 'KEY', 'IMPLEMENTS', 'THEN', 'AS',
'ELSE', 'NOT', 'CONDITION', 'CASE', 'THROW', 'METHOD', 'STATIC', 'CLONE', 'INIT', 'AT', 'ELEMENT','FINAL', 'FILE', 'NAMESPACE', 'LABEL',
'YIELD', 'GLOBAL', 'BLOCK').each{
    it.inE('INDEXED').each{
        g.removeEdge(it);
    }
}
                ";
            unset($actions['cleanIndex']);
        }

        if ($remainder = array_keys($actions)) {
            echo 'Warning : the following ', count($remainder), ' actions were ignored : ', implode(', ', $remainder), "\n";
        }

        return $qactions;
    }

    private function readConditions($conditions) {
        $queryConditions = array();

        if (isset($conditions['next'])) {
            for($i = 0; $i < $conditions['next']; ++$i) {
                $queryConditions[] = "out('NEXT')";
            }
            $queryConditions[] = "sideEffect{ a{$conditions['next']} = it;}";
            unset($conditions['next']);
        }

        if (isset($conditions['previous'])) {
            for($i = 0; $i < $conditions['previous']; ++$i) {
                $queryConditions[] = "in('NEXT')";
            }
            $queryConditions[] = "sideEffect{ b{$conditions['previous']} = it;}";
            unset($conditions['previous']);
        }

        if (isset($conditions['property'])) {
            foreach($conditions['property'] as $property => $value) {
                if (is_array($value)) {
                    $queryConditions[] = 'filter{it.'.$property." in ['".implode("', '", $value)."']}";
                } elseif ($value === true) {
                    $queryConditions[] = "has('$property', true)";
                } elseif ($value === false) {
                    $queryConditions[] = "has('$property', false)";
                } elseif ($value === 'none') {
                    $queryConditions[] = "has('$property', null)";
                } else {
                    $queryConditions[] = "has('$property', '$value')";
                }
            }
            unset($conditions['property']);
        }

        if (isset($conditions['checkForString'])) {
            if (is_array($conditions['checkForString'])) {
                $classes = "'".implode("', '", $conditions['checkForString'])."'";
            } else {
                $classes = "'".$conditions['checkForString']."'";
            }
            $queryConditions[] = "as('cfs').out('NEXT').filter{ it.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'] || it.atom in [$classes] }.loop(2){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.back('cfs')";

            unset($conditions['checkForString']);
        }

        if (isset($conditions['checkFor'])) {
            if (is_array($conditions['checkFor'])) {
                $classes = "'".implode("', '", $conditions['checkFor'])."'";
            } else {
                $classes = "'".$conditions['checkFor']."'";
            }

            $finalTokens = "'T_SEMICOLON', 'T_OPEN_CURLY', 'T_INLINE_HTML'";
            $queryConditions[] = <<<GREMLIN
filter{
    it.out('NEXT').filter{it.atom in [$classes]}.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA']}
    .loop(4){it.object.token == 'T_COMMA'}.filter{ it.token in [$finalTokens]}.any()
      }
GREMLIN;

            unset($conditions['checkFor']);
        }

        if (isset($conditions['checkNextFor'])) {
            if (is_array($conditions['checkNextFor'])) {
                $classes = "'".implode("', '", $conditions['checkNextFor'])."'";
            } else {
                $classes = "'".$conditions['checkNextFor']."'";
            }

            $finalTokens = "'T_SEMICOLON'";
            $queryConditions[] = <<<GREMLIN
filter{
    it.out('NEXT').out('NEXT').filter{it.atom in [$classes]}.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA']}
    .loop(4){it.object.token == 'T_COMMA'}.filter{ it.token in [$finalTokens]}.any()
                             }
GREMLIN;

            unset($conditions['checkNextFor']);
        }

        if (isset($conditions['checkForImplements'])) {
            if (is_array($conditions['checkForImplements'])) {
                $classes = "'".implode("', '", $conditions['checkForImplements'])."'";
            } else {
                $classes = "'".$conditions['checkForImplements']."'";
            }

            $finalTokens = "'T_OPEN_CURLY'";
            $queryConditions[] = <<<GREMLIN
filter{
    it.out('NEXT').filter{it.atom in [$classes]}.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA']}
    .loop(4){it.object.token == 'T_COMMA'}.filter{ it.token in [$finalTokens]}.any()
                             }
GREMLIN;

            unset($conditions['checkForImplements']);
        }
        
        if (isset($conditions['checkForArguments'])) {
            if (is_array($conditions['checkForArguments'])) {
                $classes = "'".implode("', '", $conditions['checkForArguments'])."'";
            } else {
                $classes = "'".$conditions['checkForArguments']."'";
            }

            $finalTokens = array_merge( Token::$alternativeEnding,
                                        array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON', 'T_CLOSE_TAG',
                                              'T_OPEN_CURLY', 'T_INLINE_HTML', 'T_CLOSE_BRACKET', 'T_ELSEIF'));
            $finalTokens = "'".implode("', '", $finalTokens)."'";
            $queryConditions[] = <<<GREMLIN
filter{ it.out('NEXT').filter{it.atom in [$classes]}.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA']}
.loop(4){!(it.object.token in [$finalTokens])}
.filter{ !(it.token in ['T_OPEN_CURLY'])}.any() }
GREMLIN;

            unset($conditions['checkForArguments']);
        }
        
        if (isset($conditions['checkForTypehint'])) {
            if (is_array($conditions['checkForTypehint'])) {
                $classes = "'".implode("', '", $conditions['checkForTypehint'])."'";
            } else {
                $classes = "'".$conditions['checkForTypehint']."'";
            }

            $finalTokens = "'T_CLOSE_PARENTHESIS'";
            $queryConditions[] = <<<GREMLIN
filter{ it.out('NEXT').transform{
    if (it.token in ['T_VARIABLE', 'T_EQUAL']) {
        it;
    } else if (it.token in ['T_STRING', 'T_ARRAY', 'T_CALLABLE', 'T_NS_SEPARATOR'] && 
               it.out('NEXT').next().token in ['T_VARIABLE', 'T_EQUAL']) {
        it.out('NEXT').next();
    } else {
        // This has to be stopped, so we stay here, and the loop will fail next loop
        it;
    }
}.out('NEXT').filter{ it.token in [$finalTokens, 'T_COMMA']}
.loop(4){!(it.object.token in [$finalTokens])}{ it.object.token in [$finalTokens]}.any() 
}
GREMLIN;

            unset($conditions['checkForTypehint']);
        }

        if (isset($conditions['checkForConcatenation'])) {
            if (is_array($conditions['checkForConcatenation'])) {
                $classes = "'".implode("', '", $conditions['checkForConcatenation'])."'";
            } else {
                $classes = "'".$conditions['checkForConcatenation']."'";
            }
            
            $finalTokens = array_merge(Token::$alternativeEnding,
                           array('T_SEMICOLON', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_BRACKET', 'T_CLOSE_CURLY', 'T_DOUBLE_ARROW', 'T_COMMA',
                                 'T_CLOSE_TAG', 'T_COLON', 'T_QUESTION', 'T_VOID',
                                 'T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND', 'T_OR',
                                 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                 'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR', 'T_AS',
                                 'T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL',
                                 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER', 'T_CLOSE_CURLY',
                                 'T_STAR', 'T_SLASH', 'T_PERCENTAGE', 'T_PLUS','T_MINUS', 'T_POW', 'T_ELSEIF', 'T_INLINE_HTML',
                                 'T_INSTANCEOF', 'T_COALESCE'));
            $finalTokens = "'".implode("', '", $finalTokens)."'";

            $queryConditions[] = <<<GREMLIN
filter{ it.out('NEXT').filter{it.atom in [$classes]}.out('NEXT').filter{ it.token in [$finalTokens, 'T_DOT']}
.loop(4){!(it.object.token in [$finalTokens])}
.filter{ !(it.token in ['T_OPEN_CURLY'])}.any() }
GREMLIN;

            unset($conditions['checkForConcatenation']);
        }

        if (isset($conditions['checkForArray'])) {

            $queryConditions[] = <<<GREMLIN
filter{ it.as('a').out('NEXT').transform{
    if (it.token == 'T_CLOSE_BRACKET') {
        it;
    } else {
        it.hasNot('atom', null).out('NEXT').filter{ it.token in ['T_CLOSE_BRACKET', 'T_CLOSE_CURLY']}.next();
    }
}.out('NEXT').loop('a'){it.object.token in ['T_OPEN_BRACKET', 'T_OPEN_CURLY'] && it.object.atom == null}.any()}
GREMLIN;
            unset($conditions['checkForArray']);
        }

        if (isset($conditions['token'])) {
            if ( is_array($conditions['token']) && !empty($conditions['token'])) {
                $queryConditions[] = "filter{it.token in ['".implode("', '", $conditions['token'])."']}";
            } elseif($conditions['token'] == 'yes') {
                $queryConditions[] = "hasNot('token', null)";
            } else {
                $queryConditions[] = "has('token', '".$conditions['token']."')";
            }
            unset($conditions['token']);
        }

        if (isset($conditions['code'])) {
            if ( is_array($conditions['code']) && !empty($conditions['code'])) {
                $queryConditions[] = "filter{it.code in ['".implode("', '", $conditions['code'])."']}";
            } else {
                $queryConditions[] = "has('code', '".$conditions['code']."')";
            }
            unset($conditions['code']);
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
                $queryConditions[] = "has('in_quote', true)";
            }
            unset($conditions['in_quote']);
        }

        if (isset($conditions['dowhile'])) {
            if ( $conditions['dowhile'] === false ) {
                $queryConditions[] = "hasNot('association', 'dowhile')";
            } else {
                $queryConditions[] = "has('association', 'dowhile')";
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

        if ($remainder = array_keys($conditions)) {
            echo 'Warning : the following ', count($remainder), ' conditions were ignored : ', implode(', ', $remainder), ' (', get_class($this), ")\n",
                 print_r($conditions, true);
        }
        
        return $queryConditions;
    }

    abstract public function fullcode() ;
}

?>
