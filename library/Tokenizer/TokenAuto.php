<?php

namespace Tokenizer;

class TokenAuto extends Token {
    protected $conditions = array();
    
    public function prepareQuery() {
        $query = " ";
        $class = str_replace("Tokenizer\\", '', get_class($this));
        if (in_array($class, array('FunctioncallArray'))) {
            $query .= "g.idx('racines')[['token':'S_ARRAY']].out('INDEXED')";
        } elseif (in_array($class, Token::$types) && !in_array($class, array('SequenceCaseDefault'))) {
            $query .= "g.idx('racines')[['token':'$class']].out('INDEXED')";
        } else {
            $query .= "g.V";
//            $query .= "g.idx('racines')[['token':'NEXT']].out('INDEXED')";
//            $query .= "g.V.has('root', 'true').in('NEXT').out('NEXT').loop(1){it.object.token != 'T_END'}{true}";
//            $query .= "g.V.has('root', 'true').in('NEXT').out('NEXT').as('x').out('NEXT').loop(2){it.object.token != 'T_END'}{true}.back('x')";
        }
        $qcdts = array();
        
        if (!empty($this->conditions[0])) {
            $qcdts = array_merge($qcdts, $this->readConditions($this->conditions[0]));
            
            $qcdts[] = "as('origin')";
        }

        for($i = -8; $i < 0; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['previous'] = abs($i);
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }

        for($i = 1; $i < 12; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['next'] = $i;
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }
        
        $query = $query.".".join('.', $qcdts);
        
        $qactions = $this->readActions($this->actions);
        $query .= ".each{\n".join(";\n", $qactions).";\n};"; // m.each{ g.removeVertex(it); };   ";
        
        return $query;
    }

    public function printQuery() {
        $query = $this->prepareQuery();
        
        print $query;
        die();
    }

    public function checkAuto() {
        $query = $this->prepareQuery();
        
        return Token::query($query);;
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
            } else {
//                print "Not removing indexing for ".get_class($this)."\n";
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
            list($what, $where) = each($actions['transfert']);
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
           unset($actions['atom']);
        }
        
        if (isset($actions['property'])) {
            if (is_array($actions['property']) && !empty($actions['property'])) {
                foreach($actions['property'] as $name => $value) {
                    $qactions[] = " /* property */   it.setProperty('$name', '$value')";
                }
            }
            unset($actions['property']);
        }
        
        if (isset($actions['order']) && is_array($actions['order'])) {
            foreach($actions['order'] as $offset => $order) {
                if ($offset > 0) {
                    $d = str_repeat(".out('NEXT')", $offset);
                } elseif ($offset < 0) {
                    $d = str_repeat(".in('NEXT')", abs($offset));
                } else {
                    $d = '';
                }
                $qactions[] = " /* order */ it$d.each{ it.setProperty('order', $order);}";
            }
            unset($actions['order']);
        }        

        if (isset($actions['makeEdge'])) {
            krsort($actions['makeEdge']);
            foreach($actions['makeEdge'] as $destination => $label) {
                display("makeEdge : $label\n");
                if ($destination > 0) {
                    $d = str_repeat(".out('NEXT')", $destination);
                    $qactions[] = "
/* makeEdge out */
f =  it".$d.".next();
g.addEdge(it, f, '$label');
g.removeEdge(f.inE('NEXT').next());

g.addEdge(it, f.out('NEXT').next(), 'NEXT');
g.removeEdge(f.outE('NEXT').next());
";
                } elseif ($destination < 0) {
                    $d = str_repeat(".in('NEXT')", abs($destination));
                    $qactions[] = "
/* makeEdge in */
f =  it".$d.".next();
g.addEdge(it, f, '$label');
g.removeEdge(f.outE('NEXT').next());

g.addEdge(f.in('NEXT').next(), it, 'NEXT');
g.removeEdge(f.inE('NEXT').next());

";
                } else {
                    print "Ignoring addEdge for 0\n";
                }
            }
            unset($actions['makeEdge']);
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
x = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');
g.addEdge(it$d, x, '$label');

";
             }
             unset($actions['add_void']);
        }

        if (isset($actions['to_var_new'])) {
            $atom = $actions['to_var_new'];
            $qactions[] = "
/* to var with arguments or not */
var = it;
arg = it.out('NEXT').next();

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').filter{it.atom in ['Variable']}.each{
    ppp = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);

    g.addEdge(null, it.in('CLASS').next(),     ppp, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
    g.addEdge(null, it.in('FUNCTION').next(),  ppp, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
    g.addEdge(null, it.in('NAMESPACE').next(), ppp, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
    g.addEdge(null, it.in('FILE').next(),      ppp, 'FILE',      [file: it.inE('FILE').next().file]);
    
    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tvoid, 'FILE');
    g.addEdge(ppp, tvoid, 'VALUE');
    
    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tstatic, 'FILE');
    g.addEdge(ppp, tstatic, var.code.toUpperCase());
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    ppp = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);
    g.addEdge(null, it.in('CLASS').next(),     ppp, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
    g.addEdge(null, it.in('FUNCTION').next(),  ppp, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
    g.addEdge(null, it.in('NAMESPACE').next(), ppp, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
    g.addEdge(null, it.in('FILE').next(),      ppp, 'FILE',      [file: it.inE('FILE').next().file]);

    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(ppp, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    tstatic = g.addVertex(null, [code:var.code, atom:'$atom', token:'T_STATIC', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tstatic, 'FILE');
    g.addEdge(ppp, tstatic, var.code.toUpperCase());
    
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
}

g.addEdge(root, var.out('NEXT').out('NEXT').next(), 'NEXT');
g.removeEdge(var.out('NEXT').outE('NEXT').next());
g.removeVertex(arg);

g.addEdge(var.in('NEXT').next(), var.out('NEXT').next(), 'NEXT');
var.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(var);

";
            unset($actions['to_var_new']);
        }
        
        if (isset($actions['to_var'])) {
            $atom = $actions['to_var'];
            $qactions[] = "
/* to var with arguments */
var = it;
arg = it.out('NEXT').next();

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').filter{it.atom in ['Variable', 'Static', 'Ppp']}.each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), x, 'FILE');
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tvoid, 'FILE');
    g.addEdge(x, tvoid, 'VALUE');
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), x, 'FILE');
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(x, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
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
            $atom = $actions['to_global'];
            $qactions[] = "
/* to global with arguments */
var = it;
arg = it.out('NEXT').next();

root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').each{
    x = g.addVertex(null, [code:'global', atom:'Global', token:'T_GLOBAL', virtual:true, line:it.line]);
    
    g.addEdge(var, x, 'ELEMENT');
    g.addEdge(x,  it, 'NAME');
    
    it.inE('ARGUMENT').each{ g.removeEdge(it); }
}

g.addEdge(root, var.out('NEXT').out('NEXT').next(), 'NEXT');
g.removeEdge(var.out('NEXT').outE('NEXT').next());
g.removeVertex(arg);

var.setProperty('code', ';');
var.setProperty('atom', 'Sequence');
var.setProperty('token', 'T_GLOBAL');
var.inE('INDEXED').each{ g.removeEdge(it); }

";
            unset($actions['to_global']);
        }

        if (isset($actions['to_var_ppp'])) {
            list($atom1, $atom2) = $actions['to_var_ppp'];
            $qactions[] = "
/* to var with another ppp before (private static or static private) */

var = it;
arg = it.out('NEXT').next();
arg2 = it.in('NEXT').next();


root = it;
root.setProperty('code', var.code);
root.setProperty('token', var.token);

arg.out('ARGUMENT').filter{ it.atom in ['Variable']}.each{
    ppp = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);
    g.addEdge(null, it.in('CLASS').next(),     ppp, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
    g.addEdge(null, it.in('FUNCTION').next(),  ppp, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
    g.addEdge(null, it.in('NAMESPACE').next(), ppp, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
    g.addEdge(null, it.in('FILE').next(),      ppp, 'FILE',      [file: it.inE('FILE').next().file]);

    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it, 'DEFINE');
    g.removeEdge(it.inE('ARGUMENT').next());
    tvoid = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tvoid, 'FILE');
    g.addEdge(ppp, tvoid, 'VALUE');
    
    atom1 = g.addVertex(null, [code:var.code, atom:'$atom1', token:var.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), atom1, 'FILE');
    g.addEdge(ppp, atom1, var.code.toUpperCase());
    
    atom2 = g.addVertex(null, [code:arg2.code, atom:'$atom2', token:arg2.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), atom2, 'FILE');
    g.addEdge(ppp, atom2, arg2.code.toUpperCase());
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    ppp = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);
    g.addEdge(null, it.in('CLASS').next(),     ppp, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
    g.addEdge(null, it.in('FUNCTION').next(),  ppp, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
    g.addEdge(null, it.in('NAMESPACE').next(), ppp, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
    g.addEdge(null, it.in('FILE').next(),      ppp, 'FILE',      [file: it.inE('FILE').next().file]);

    g.addEdge(root, ppp, 'NEXT');
    root = ppp;

    g.addEdge(ppp, it.out('LEFT').next(), 'DEFINE');
    g.addEdge(ppp, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    atom1 = g.addVertex(null, [code:var.code, atom:'$atom1', token:var.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), atom1, 'FILE');
    g.addEdge(ppp, atom1, var.code.toUpperCase());
    
    atom2 = g.addVertex(null, [code:arg2.code, atom:'$atom2', token:arg2.token, virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), atom2, 'FILE');
    g.addEdge(ppp, atom2, arg2.code.toUpperCase());
    
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
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

        if (isset($actions['to_use'])) {
            $qactions[] = "
/* to use with arguments */
var = it;
arg = it.out('NEXT').next();

var.out('NEXT').has('atom', 'Arguments').out('ARGUMENT').each{
    
    g.addEdge(var, it, 'USE');
    g.removeEdge(it.inE('ARGUMENT').next());
}

d = it.out('NEXT').out('NEXT').next();
it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }

g.addEdge(var, d, 'NEXT');
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), arg, 'DELETE');   

";
            unset($actions['to_use']);
        }

        if (isset($actions['to_lambda'])) {
            $qactions[] = "
/* to to_lambda function */

x = g.addVertex(null, [code:'lambda', atom:'String', token:'T_STRING', virtual:true, line:it.line]);
g.addEdge(it, x, 'NAME');

op = it.out('NEXT').next();
cp = it.out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(it, it.out('NEXT').out('NEXT').next(), 'ARGUMENTS');
block = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();
g.addEdge(it, block, 'BLOCK');

g.addEdge(it, block.out('NEXT').next(), 'NEXT');

g.removeEdge(block.outE('NEXT').next());

op.bothE('NEXT').each{ g.removeEdge(it); }
cp.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), op, 'DELETE');   
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), cp, 'DELETE');   

";
            unset($actions['to_lambda']);
        }

        if (isset($actions['to_lambda_use'])) {
            $qactions[] = "
/* to to_lambda function with use */

x = g.addVertex(null, [code:'lambda', atom:'String', token:'T_STRING', virtual:true, line:it.line]);
g.addEdge(it, x, 'NAME');

x = it.out('NEXT').next();
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), x, 'DELETE');   
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(it, x, 'ARGUMENTS');

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), x, 'DELETE');   

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), x, 'DELETE');   

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), x, 'DELETE');   

x = x.out('NEXT').next();
g.addEdge(it, x, 'USE');

x = x.out('NEXT').next();
x.in('NEXT').outE('NEXT').each{ g.removeEdge(it); }
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), x, 'DELETE');   

x = x.out('NEXT').next();
g.addEdge(it, x, 'BLOCK');

x = x.out('NEXT').next();
g.removeEdge(x.inE('NEXT').next());
g.addEdge(it, x, 'NEXT');   

";
            unset($actions['to_lambda_use']);
        }

        if (isset($actions['to_ppp'])) {
            $qactions[] = "
/* to ppp alone */
x = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);
g.addEdge(null, it.in('CLASS').next(),     x, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
g.addEdge(null, it.in('FUNCTION').next(),  x, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
g.addEdge(null, it.in('NAMESPACE').next(), x, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
g.addEdge(null, it.in('FILE').next(),      x, 'FILE',      [file: it.inE('FILE').next().file]);

g.addEdge(x, it.out('NEXT').next(), 'DEFINE');
it.out('NEXT').has('atom', 'Variable').each {
    tvoid = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), tvoid, 'FILE');
    g.addEdge(x, tvoid, 'VALUE');
}
g.addEdge(x, it, it.code.toUpperCase());

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

variable = it.out('NEXT').next();
variable.bothE('NEXT').each{ g.removeEdge(it); }
variable.bothE('INDEXED').each{ g.removeEdge(it); }

g.removeEdge( it.inE('NEXT').next());
";
            unset($actions['to_ppp']);
        }        

        if (isset($actions['to_ppp2'])) {
            $qactions[] = "
/* to ppp already ppp */

g.addEdge(it.out('NEXT').next() , it, it.code.toUpperCase());

p = it.in('NEXT').next();
ppp = it.out('NEXT').next();

it.bothE('NEXT').each{ g.removeEdge(it); }
g.addEdge(p, ppp, 'NEXT');

";
            unset($actions['to_ppp2']);
        }        

        if (isset($actions['to_option'])) {
            $qactions[] = "
/* turn the current token to an option of the next */

g.addEdge(it.out('NEXT').next() , it, it.code.toUpperCase());
g.addEdge(it.in('NEXT').next() , it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['to_option']);
        }    
        
        
        if (isset($actions['to_ppp_assignation'])) {
            $qactions[] = "
/* to ppp with assignation */

x = g.addVertex(null, [code:'Ppp', atom:'Ppp', token:'T_PPP', virtual:true, line:it.line]);
g.addEdge(null, it.in('CLASS').next(),     x, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
g.addEdge(null, it.in('FUNCTION').next(),  x, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
g.addEdge(null, it.in('NAMESPACE').next(), x, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
g.addEdge(null, it.in('FILE').next(),      x, 'FILE',      [file: it.inE('FILE').next().file]);

g.addEdge(x, it.out('NEXT').out('LEFT').next(), 'DEFINE');
g.addEdge(x, it.out('NEXT').out('RIGHT').next(), 'VALUE');
g.addEdge(x, it, it.code.toUpperCase());

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

assignation = it.out('NEXT').next();
assignation.bothE('NEXT').each{ g.removeEdge(it); }
g.removeVertex(assignation);

g.removeEdge( it.inE('NEXT').next());
";
            unset($actions['to_ppp_assignation']);
        }
        
        if (isset($actions['transform'])) {
            $c = 0; 
            
            foreach($actions['transform'] as $destination => $label) {
                if ($label == 'NONE') { continue; }
                if ($destination > 0) { 
                    $c++;
                
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop out ($c) */
f = [];
it.out('NEXT').fill(f);
h = it;
f.each{
    i = it; 
    it.out('NEXT').each{ g.addEdge(h, it, 'NEXT');}

    g.removeVertex(i);
}
";
                    } elseif ($label == 'SEQUENCE') {
                        $qactions[] = "
/* transform next to sequence */
x = g.addVertex(null, [code:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

b = it.out('NEXT').next();
g.addEdge(it, x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

g.addEdge(x, it.out('NEXT').next(), 'ELEMENT');
b.bothE('NEXT').each{ g.removeEdge(it); }

it.out('NEXT').out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it) ; }
    g.removeVertex(it);
}

";
                    } elseif (substr($label, 0, 3) == 'TO_') {
                        $link = substr($label, 3);
                        $qactions[] = "
/* transform to var out ($c) */
n = it.out('NEXT').next();
h = it;
n.out('ARGUMENT').each{
    g.addEdge(h, it, '$link');
    g.removeEdge(it.inE('ARGUMENT').next());
}

f = it.out('NEXT').out('NEXT').next();
g.removeEdge(it.out('NEXT').outE('NEXT').next());
g.removeEdge(it.outE('NEXT').next());
g.addEdge(it, f,  'NEXT');

g.removeVertex(n);
";
                    } else {
                        $qactions[] = "
/* transform out ($c) */
f =  it.out('NEXT').next();
g.addEdge(it, f, '$label');
g.removeEdge(f.inE('NEXT').next());

g.addEdge(it, f.out('NEXT').next(), 'NEXT');
g.removeEdge(f.outE('NEXT').next());

";
                    }
                } elseif ($destination < 0) {
                    if ($label == 'DROP') {
                        $qactions[] = "
/* transform drop in ($c) */
f = [];
it.in('NEXT').fill(f);
h = it;
f.each{
    i = it; 
    it.in('NEXT').each{ g.addEdge(it, h, 'NEXT');}

    g.removeVertex(i);
}

";
                    } else {
                        $qactions[] = "
/* transform in ($c) */
f =  it.in('NEXT').next();
g.addEdge(it, f, '$label');
g.removeEdge(f.outE('NEXT').next());

g.addEdge(f.in('NEXT').next(), it, 'NEXT');
g.removeEdge(f.inE('NEXT').next());

";
                    }
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
                    } else {
                        die("Destination 0 for transform\n");
                    }
                }
            }

            unset($actions['transform']);
        }

        if (isset($actions['dropNext'])) {
            foreach($actions['dropNext'] as $destination) {
                if ($destination > 0) {
                    $d = str_repeat(".out('NEXT')", $destination);
                    $qactions[] = "
/* dropNext out */
f = [];
it".$d.".fill(f);
h = it;
f.each{
    i = it; 
    it.out('NEXT').each{ g.addEdge(h, it, 'NEXT');}

    g.removeVertex(i);
}

";
                } elseif ($destination < 0) {
                    die('No support for negative dropNext');
                } else {
                    print "Ignoring addEdge for 0\n";
                }
            }
            unset($actions['dropNext']);
        }

        if (isset($actions['to_const_assignation'])) {
            $qactions[] = "
/* transform to const assignation to const a=1; */
    a = it.in('NEXT').next();
    b = it.out('NEXT').out('NEXT').next();
    x = g.addVertex(null, [code:'const', atom:'Const', token:'T_CONST', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), x, 'FILE');
    
    g.addEdge(x, it.out('NEXT').out('LEFT').next(), 'NAME');
    g.addEdge(x, it.out('NEXT').out('RIGHT').next(), 'VALUE');
    
    g.removeEdge(it.out('NEXT').outE('LEFT').next());
    g.removeEdge(it.out('NEXT').outE('RIGHT').next());
    
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it.out('NEXT').next(), 'DELETE');
    it.out('NEXT').bothE('NEXT').each{ g.removeEdge(it); }
    it.inE('NEXT').each{ g.removeEdge(it); }

    g.addEdge(a, x, 'NEXT');
    g.addEdge(x, b, 'NEXT');

"; 
            unset($actions['to_const_assignation']);
        }
        
        if (isset($actions['to_const'])) {
            $qactions[] = "
/* transform to const a=1 ,  b=2 => const a=1; const b=2 */

sequence = g.addVertex(null, [code:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), sequence, 'FILE');
_const = it;
arg = _const.out('NEXT').next(); 

g.addEdge(_const.in('NEXT').next(), sequence, 'NEXT');
g.addEdge(sequence, _const.out('NEXT').out('NEXT').out('NEXT').next(), 'NEXT');
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), _const.out('NEXT').out('NEXT').next(), 'DELETE');   

g.removeEdge(_const.out('NEXT').out('NEXT').outE('NEXT').next());
g.removeEdge(_const.out('NEXT').outE('NEXT').next());
_const.bothE('NEXT').each{ g.removeEdge(it); }

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    x = g.addVertex(null, [code:'const', atom:'Const', token:'T_CONST', virtual:true, line:it.line]);
    g.addEdge(it.in('FILE').next(), x, 'FILE');
    
    g.addEdge(sequence, x, 'ELEMENT');
    root = x;

    g.addEdge(x, it.out('LEFT').next(), 'NAME');
    g.addEdge(x, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
}

g.addEdge(g.idx('racines')[['token':'DELETE']].next(), arg, 'DELETE');   
g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   

//FINISH
"; 
            unset($actions['to_const']);
}

        if (isset($actions['dropNextCode'])) {
            foreach($actions['dropNextCode'] as $destination) {
                $d = str_repeat(".out('NEXT')", 1);
                $qactions[] = "
/* dropNextCode out */
f = [];
it.out('NEXT').has('code', '$destination').fill(f);
h = it;
f.each{
    i = it; 
    it.out('NEXT').each{ g.addEdge(h, it, 'NEXT');}

    g.removeVertex(i);
}

";
            }
            unset($actions['dropNextCode']);
        }

        if (isset($actions['insertConcat'])) {
                $qactions[] = "
/* insertConcat */
x = g.addVertex(null, [code:'{$actions['insertConcat']}', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

g.addEdge(x, it, 'CONCAT');
g.addEdge(x, it.out('NEXT').next(), 'CONCAT');

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

it.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

x.out('CONCAT').has('atom', 'Concatenation').each{
    it.out('CONCAT').each{
        it.inE('CONCAT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'CONCAT');
    }
    g.removeEdge(it.inE('CONCAT').next());
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
    //g.removeVertex(it);
}
";
            unset($actions['insertConcat']);
        }

        if (isset($actions['insertSequence'])) {
            $qactions[] = "
/* insertSequence */
x = g.addVertex(null, [code:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, modifiedBy:'SequenceAtom', line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

g.addEdge(x, it, 'ELEMENT');
g.addEdge(x, it.out('NEXT').next(), 'ELEMENT');

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

it.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

x.as('origin').out('ELEMENT').has('atom','Sequence').each{
    it.inE('ELEMENT').each{ g.removeEdge(it);}
  
    it.out('ELEMENT').each{ 
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    };

    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
//    g.removeVertex(it);    
}


/* Remove children's index */  
x.outE.hasNot('label', 'NEXT').inV.each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

";
            unset($actions['insertSequence']);
        }

        if (isset($actions['insertSequenceCaseDefault'])) {
                $qactions[] = "
/* insertSequenceCaseDefault */
x = g.addVertex(null, [code:'Sequence Case Default', atom:'SequenceCaseDefault', token:'T_SEQUENCE_CASEDEFAULT', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

g.addEdge(x, it, 'ELEMENT');
g.addEdge(x, it.out('NEXT').next(), 'ELEMENT');

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

it.setProperty('order2', 1);
it.out('NEXT').next().setProperty('order2', 2);

it.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

m  = [];
x.out('ELEMENT').store(m).out('ELEMENT').store(m).iterate();

x.out('ELEMENT').has('atom', 'SequenceCaseDefault').each{
    it.out('ELEMENT').each{
        it.inE('ELEMENT').each{ g.removeEdge(it);}
        g.addEdge(x, it, 'ELEMENT');
    }
    g.removeEdge(it.inE('ELEMENT').next());
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
}

";
            unset($actions['insertSequenceCaseDefault']);
        }
        
        if (isset($actions['createSequenceForCaseWithoutSemicolon'])) {
            $qactions[] = "

/* createSequenceForCaseWithoutSemicolon */ 
x = g.addVertex(null, [code:'Block With Sequence For Case Without Semicolon', token:'T_SEMICOLON', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').out('NEXT').next();
g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'ELEMENT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }


a = x.out('NEXT').next();
g.addEdge(x, a, 'ELEMENT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
a.bothE('NEXT').each{ g.removeEdge(it) ; }

x.out('ELEMENT').has('token', 'T_SEMICOLON').each{
    it.out('ELEMENT').each{
        g.addEdge(x, it, 'ELEMENT');
        g.removeEdge(it.inE('ELEMENT').next());
    }
    
    g.removeVertex(it);
}

x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it) ; }
    
    g.removeVertex(it);
}

";
            unset($actions['createSequenceForCaseWithoutSemicolon']);
        }        
        
        if (isset($actions['createSequenceForDefaultWithoutSemicolon'])) {
            $qactions[] = "

/* createSequenceForDefaultWithoutSemicolon */ 
x = g.addVertex(null, [code:'Block With Sequence For Default Without Semicolon', token:'T_SEMICOLON', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').next();
g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'ELEMENT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }


a = x.out('NEXT').next();
g.addEdge(x, a, 'ELEMENT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
a.bothE('NEXT').each{ g.removeEdge(it) ; }

x.out('ELEMENT').has('token', 'T_SEMICOLON').each{
    it.out('ELEMENT').each{
        g.addEdge(x, it, 'ELEMENT');
        g.removeEdge(it.inE('ELEMENT').next());
    }
    
    g.removeVertex(it);
}

x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it) ; }
    
    g.removeVertex(it);
}

";
            unset($actions['createSequenceForDefaultWithoutSemicolon']);
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

g.removeVertex(d);
g.removeVertex(it);

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

        if (isset($actions['to_typehint'])) {
            $qactions[] = "
/* to type hint */
x = g.addVertex(null, [code:'Typehint', atom:'Typehint', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').out('NEXT').next(), 'NEXT');

g.addEdge(x, a, 'CLASS');
g.addEdge(x, a.out('NEXT').next(), 'VARIABLE');

a.out('NEXT').bothE('NEXT').each{ g.removeEdge(it);}    
a.bothE('NEXT').each{ g.removeEdge(it);}    

/* Remove children's index */  
x.outE.hasNot('label', 'NEXT').inV.each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

";
            unset($actions['to_typehint']);
        }              
        
        if (isset($actions['insertEdge'])) {
            foreach($actions['insertEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $qactions[] = "
/* insertEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom', virtual:true, line:it.line, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

f = it.out('NEXT').out('NEXT').next();

g.addEdge(it, x, 'NEXT');
g.addEdge(x, f, 'NEXT');
g.addEdge(x, it.out('NEXT').next(), '$link');
g.removeEdge(it.outE('NEXT').next());
g.removeEdge(x.out('$link').outE('NEXT').next());

x.out('$link').inE('INDEXED').each{    
    g.removeEdge(it);
} 

";
            } else {
                print "No support for insertEdge with destination 0 or less\n";
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
x = g.addVertex(null, [code:'void', atom:'$atom', token:'T_VOID', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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
x = g.addVertex(null, [code:'void', token:'T_VOID', atom:'$atom', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');
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
x = g.addVertex(null, [code:'void', token:'T_VOID', atom:'$atom', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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
f = it;
c = it.out('$link').out('$link').count();
it.out('$link').hasNot('order', null).each{
    it.setProperty('order', it.order + c);
}

it.as('origin').out('$link').has('atom','$atom').each{
    it.inE('$link').each{ g.removeEdge(it);}
  
    it.out('$link').each{ 
        it.inE('$link').each{ g.removeEdge(it);}
        g.addEdge(f, it, '$link');
    };
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
}
            ";
            }
            unset($actions['mergeNext']);
        }
        
        if (isset($actions['createSequenceWithNext']) && $actions['createSequenceWithNext']) {
                $qactions[] = " 
/* createSequenceWithNext */ 

x = g.addVertex(null, [code:'Sequence With Next', atom:'Sequence', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');
i = it.out('NEXT').next();

g.addEdge(it, x, 'NEXT');
g.addEdge(x, i, 'ELEMENT');
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
            $qactions[] = " 
/* to_block_else */

it.out('NEXT').has('token', 'T_COLON').each{
    endif = it.out('NEXT').out('NEXT').next();
    
    // removing the colon
    g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');

    // removing the endif
    g.addEdge(endif.in('NEXT').next(), endif.out('NEXT').next(), 'NEXT');
    endif.bothE('NEXT').each{ g.removeEdge(it); }
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), endif, 'DELETE');
}

x = g.addVertex(null, [code:'Block With else', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    semicolon = it;
    g.addEdge(x, it.out('NEXT').next(), 'NEXT');
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

/* Clean index */
x.out('CODE').each{ 
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

x = g.addVertex(null, [code:'Block With Foreach', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
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
        
        if (isset($actions['to_block_for']) && $actions['to_block_for']) {
                $qactions[] = " 
/* to_block_for */ 

x = g.addVertex(null, [code:'Block With For', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
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

        if (isset($actions['to_block_ifelseif']) && $actions['to_block_ifelseif']) {
                $qactions[] = " 
/* to_block_ifelseif */ 

x = g.addVertex(null, [code:'Block With if/elseif', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), semicolon, 'DELETE');   
}

/* Clean index */
x.out('CODE').each{ 
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

x = g.addVertex(null, [code:'Block With control if elseif', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
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
x = g.addVertex(null, [code:'Block With Next', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it, 'CODE');
g.addEdge(x, it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it) ; }
            ";
            unset($actions['createBlockWithSequence']);
        }
        
        if (isset($actions['createBlockWithSequenceForCase']) && $actions['createBlockWithSequenceForCase']) {
            $qactions[] = " 
/* createBlockWithSequenceForCase */ 
x = g.addVertex(null, [code:'Block With Sequence For Case', token:'T_SEMICOLON', atom:'Block', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').out('NEXT').next();

a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'CODE');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }

/* Clean index */
x.out('CODE').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

            ";
            unset($actions['createBlockWithSequenceForCase']);
        }

        if (isset($actions['createBlockWithSequenceForDefault']) && $actions['createBlockWithSequenceForDefault']) {
            $qactions[] = " 
/* createBlockWithSequenceForDefault */ 
x = g.addVertex(null, [code:'Block With Sequence For Default', atom:'Block', token:'T_SEMICOLON', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').next();

a.out('NEXT').has('token', 'T_SEMICOLON').each{
    g.addEdge(a, it.out('NEXT').next(), 'NEXT');
    it.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(it);
}

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a, 'CODE');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');

a.bothE('NEXT').each{ g.removeEdge(it) ; }

/* Clean index */
x.out('CODE').each{ 
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
x = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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
x = g.addVertex(null, [code:'Void', atom:'Void', token:'T_VOID', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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

    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
}
";
            }
            unset($actions['mergePrev2']);
        }
        
        if (isset($actions['mergePrev']) && $actions['mergePrev']) {
            foreach($actions['mergePrev'] as $atom => $link) {
                $qactions[] = " 
/* mergePrev */ 
x = g.addVertex(null, [code:';', atom:'Sequence', token:'T_SEMICOLON', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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

    g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');   
    //g.removeVertex(it);
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
            $atom = $actions['make_quoted_string'];
            $qactions[] = " 
/* make_quoted_string */ 
x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

it.out('NEXT').loop(1){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}{!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}.each{
    g.addEdge(x, it, 'CONCAT');
    it.inE('NEXT').each{ g.removeEdge(it);}
    f = it;
}

g.addEdge(it, x, 'CONTAIN');
g.addEdge(it, f.out('NEXT').out('NEXT').next(), 'NEXT');

g.addEdge(g.idx('racines')[['token':'DELETE']].next(), f.out('NEXT').next(), 'DELETE');
g.removeEdge(f.out('NEXT').outE('NEXT').next());

it.setProperty('atom', '$atom');

/* Clean index */
x.out('CONCAT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}
            ";
            unset($actions['make_quoted_string']);
        }
        
        if (isset($actions['mergeConcat'])) {
            $qactions[] = " 
/* mergeConcat */ 
x = g.addVertex(null, [code:'Concatenation', atom:'Concatenation', token:'T_DOT', virtual:true, line:it.line]);
g.addEdge(it.in('FILE').next(), x, 'FILE');

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

g.addEdge(g.idx('racines')[['token':'DELETE']].next(), it, 'DELETE');
//    g.removeVertex(it);    
}

/* Clean index */
x.out('ELEMENT').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}

            ";
            unset($actions['mergeConcat']);
        }
        
        if (isset($actions['while_to_block'])) {
            $qactions[] = " 
/* while_to_block */  

x = g.addVertex(null, [code:'Block With While', token:'T_BLOCK', atom:'Block', virtual:true, line:it.line, modifiedBy:'_While']);
g.addEdge(it.in('FILE').next(), x, 'FILE');

a = it.out('NEXT').out('NEXT').out('NEXT').out('NEXT').next();

g.addEdge(a.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, a.out('NEXT').next(), 'NEXT');
g.addEdge(x, a, 'CODE');
a.bothE('NEXT').each{ g.removeEdge(it); }

// remove the next, if this is a ; 
x.out('NEXT').has('token', 'T_SEMICOLON').has('atom', null).each{
    g.addEdge(x, x.out('NEXT').out('NEXT').next(), 'NEXT');
    semicolon = it;
    semicolon.bothE('NEXT').each{ g.removeEdge(it); }
    g.removeVertex(semicolon);
}

/* Clean index */
x.out('CODE').each{ 
    it.inE('INDEXED').each{    
        g.removeEdge(it);
    } 
}
                ";
            unset($actions['while_to_block']);
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
x.setProperty('atom', 'Block');

                ";
            unset($actions['while_to_empty_block']);
        }        


        if (isset($actions['variable_to_functioncall'])) {
            $qactions[] = " 
/* create a functioncall, and hold the variable as property.  */  

x = g.addVertex(null, [code:it.code, atom:'Variable', token:'T_VARIABLE', virtual:true, line:it.line, modifiedBy:'FunctionCall']);

g.addEdge(it, x, 'NAME');

g.addEdge(null, it.in('CLASS').next(),     x, 'CLASS'    , [classname: it.inE('CLASS').next().classname]);
g.addEdge(null, it.in('FUNCTION').next(),  x, 'FUNCTION' , [function: it.inE('FUNCTION').next().function]);
g.addEdge(null, it.in('NAMESPACE').next(), x, 'NAMESPACE', [namespace: it.inE('NAMESPACE').next().namespace]);
g.addEdge(null, it.in('FILE').next(),      x, 'FILE',      [file: it.inE('FILE').next().file]);

                ";
            unset($actions['variable_to_functioncall']);
        }        
        if (isset($actions['cleanIndex'])) {
            $e = $actions['cleanIndex'];
            $qactions[] = " 
/* Remove children's index */  
it.outE.hasNot('label', 'NEXT').inV.each{ 
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
            unset($cdt['next']);
        }

        if (isset($cdt['previous'])) {
            for($i = 0; $i < $cdt['previous']; $i++) {
                $qcdts[] = "in('NEXT')";
            }
            unset($cdt['previous']);
        }

        if (isset($cdt['check_for_string'])) {
            if (is_array($cdt['check_for_string'])) {
                $classes = "'".join("', '", $cdt['check_for_string'])."'";
            } else {
                $classes = "'".$cdt['check_for_string']."'";
            }
            $qcdts[] = "as('cfs').out('NEXT').filter{ it.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'] || it.atom in [$classes] }.loop(2){!(it.object.token in ['T_QUOTE_CLOSE', 'T_END_HEREDOC', 'T_SHELL_QUOTE_CLOSE'])}{true}.back('cfs')";
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
}

?>