<?php

namespace Tokenizer;

class TokenAuto extends Token {
    protected $conditions = array();
    
    public function prepareQuery() {
        $query = "g.V";
        $qcdts = array();
        
        if (!empty($this->conditions[0])) {
            $qcdts = array_merge($qcdts, $this->readConditions($this->conditions[0]));
            
            $qcdts[] = "as('origin')";
        }

        for($i = -4; $i < 0; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['previous'] = abs($i);
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }

        for($i = 1; $i < 7; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['next'] = $i;
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }
        
        /*
        if (count(Token::$reserved) != 0) {
            $cdt = array();
            $cdt['next'] = max(array_keys($this->conditions)) + 1;
            $cdt['filterOut'] = Token::$reserved;
                        
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));
            $qcdts[] = "back('origin')";
        }
        */
        
        $query = $query.".".join('.', $qcdts);
        
        $qactions = $this->readActions($this->actions);
        $query .= ".each{\n".join(";\n", $qactions).";\n}";
        $qcdts[] = "back('origin')";
        
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
        
        if (isset($actions['atom'])) {
           $qactions[] = " /* atom */   it.setProperty('atom', '".$actions['atom']."')";
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
x = g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', 'file':it.file]);
g.addEdge(it$d, x, '$label');

";
             }
             unset($actions['add_void']);
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

arg.out('ARGUMENT').has('atom', 'Variable').each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, 'file':arg.file]);
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it, 'NAME');
    g.removeEdge(it.inE('ARGUMENT').next());
    g.addEdge(x, g.addVertex(null, [code:'void', atom:'Void', token:'T_VOID', 'file':arg.file]), 'VALUE');
}

arg.out('ARGUMENT').has('atom', 'Assignation').each{
    x = g.addVertex(null, [code:var.code, atom:'$atom', token:var.token, 'file':arg.file]);
    
    g.addEdge(root, x, 'NEXT');
    root = x;

    g.addEdge(x, it.out('LEFT').next(), 'NAME');
    g.addEdge(x, it.out('RIGHT').next(), 'VALUE');
    g.removeEdge(it.outE('LEFT').next());
    g.removeEdge(it.outE('RIGHT').next());
    
    g.removeVertex(it);
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
            $qactions[] = "
/* to global with arguments */


";
            unset($actions['to_global']);
        }
        
        if (isset($actions['to_ppp'])) {
            $qactions[] = "
/* to ppp with arguments */
g.addEdge(it, it.out('NEXT').out('LEFT').next(), 'NAME');
g.addEdge(it, it.out('NEXT').out('RIGHT').next(), 'VALUE');

assignation = it.out('NEXT').next();
g.addEdge(it, assignation.out('NEXT').next(), 'NEXT');
assignation.bothE().each{ g.removeEdge(it); }
g.removeVertex(assignation);

";
            unset($actions['to_ppp']);
        }
        
        if (isset($actions['transform'])) {
            $c = 0; 
            foreach($actions['transform'] as $destination => $label) {
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
                    } elseif ($label == 'TO_CONST') {
                        $qactions[] = "
/* transform to const ($c) */
a = it.out('NEXT').next();

a.setProperty('code', 'const');
a.setProperty('atom', 'Const');

b = a.out('NEXT').next();

f = a.out('NEXT').out('NEXT').out('NEXT').next();
g.addEdge(a, b, 'NAME');
g.addEdge(a, f, 'VALUE');
g.addEdge(a, f.out('NEXT').next(), 'NEXT');

g.removeVertex(b.out('NEXT').next());

b.bothE('NEXT').each{ g.removeEdge(it); }
f.bothE('NEXT').each{ g.removeEdge(it); }

";                    
                    } elseif ($label == 'SEQUENCE') {
                        $qactions[] = "
/* transform next to sequence */
x = g.addVertex(null, [code:'Sequence', atom:'Sequence', token:'T_SEMICOLON', 'file':it.file]);

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
                    die("Destination 0 pour transform ");
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
x = g.addVertex(null, [code:'{$actions['insertConcat']}', atom:'Concatenation', token:'T_DOT', 'file':it.file]);

p = it.in('NEXT').next();
n = it.out('NEXT').out('NEXT').next();
a = it
b = it.out('NEXT').next();


g.removeEdge(p.outE('NEXT').next());
g.addEdge(p, x, 'NEXT');
g.removeEdge(n.inE('NEXT').next());
g.addEdge(x, n, 'NEXT');

g.addEdge(x, a, 'CONCAT');
g.addEdge(x, b, 'CONCAT');
g.removeEdge(b.inE('NEXT').next());

";
            unset($actions['insertConcat']);
        }

        if (isset($actions['insertSequence'])) {
                $qactions[] = "
/* insertSequence */
x = g.addVertex(null, [code:'Sequence', atom:'Sequence', token:'T_SEMICOLON', 'file':it.file]);

g.addEdge(x, it, 'ELEMENT');
g.addEdge(x, it.out('NEXT').next(), 'ELEMENT');

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

it.out('NEXT').outE('NEXT').each{ g.removeEdge(it); }
it.bothE('NEXT').each{ g.removeEdge(it); }

";
            unset($actions['insertSequence']);
        }

        if (isset($actions['insertConcat2'])) {
            $qactions[] = "
/* insertConcat 2 */
x = it;
it.out('NEXT').out('CONCAT').each{ g.addEdge(x, it, 'CONCAT'); };
it.out('NEXT').outE('CONCAT').each{ g.removeEdge(it); };
    
g.addEdge(x, it.out('NEXT').out('NEXT').next(), 'NEXT');

//it.out('NEXT').next().setProperty('code', 'Destroyed');
g.removeVertex(it.out('NEXT').next());

";
            unset($actions['insertConcat2']);
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
        
        
        if (isset($actions['insertEdge'])) {
            foreach($actions['insertEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $qactions[] = "
/* insertEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom', 'file':it.file]);
f = it.out('NEXT').out('NEXT').next();

g.addEdge(it, x, 'NEXT');
g.addEdge(x, f, 'NEXT');
g.addEdge(x, it.out('NEXT').next(), '$link');
g.removeEdge(it.outE('NEXT').next());
g.removeEdge(x.out('$link').outE('NEXT').next());

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
x = g.addVertex(null, [code:'void', atom:'$atom', 'file':it.file]);
f = it.out('NEXT').next();

g.removeEdge(it.outE('NEXT').next());
g.addEdge(it, x, 'NEXT');
g.addEdge(x,  f, 'NEXT');

";
            } else {
                print "No support for addEdge with destination 0 or less\n";
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
    g.removeVertex(it);    
//    it.setProperty('code', it.code + ' mergeNext');
}
            ";
            }
            unset($actions['mergeNext']);
        }
        
        if (isset($actions['createSequenceWithNext']) && $actions['createSequenceWithNext']) {
                $qactions[] = " 
/* createSequenceWithNext */ 

x = g.addVertex(null, [code:'Sequence With Next', atom:'Sequence', 'file':it.file]);
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

        if (isset($actions['createBlockWithSequence']) && $actions['createBlockWithSequence']) {
                $qactions[] = " 
/* createBlockWithSequence */ 
x = g.addVertex(null, [code:'Block With Next', atom:'Block', 'file':it.file]);

g.addEdge(it.in('NEXT').next(), x, 'NEXT');
g.addEdge(x, it, 'CODE');
g.addEdge(x, it.out('NEXT').next(), 'NEXT');

it.bothE('NEXT').each{ g.removeEdge(it) ; }
            ";
            unset($actions['createBlockWithSequence']);
        }
        
        if (isset($actions['mergePrev']) && $actions['mergePrev']) {
            foreach($actions['mergePrev'] as $atom => $link) {
                $qactions[] = " 
/* mergePrev */ 
f = it;
//c = it.out('$link').out('$link').count();
//it.out('$link').hasNot('order', null).each{
//    it.setProperty('order', it.order + c);
//}

it.as('origin').in('$link').has('atom','$atom').each{
    it.outE('$link').each{ g.removeEdge(it);}
    
    it.out('$link').each{ 
        it.inE('$link').each{ g.removeEdge(it);}
        g.addEdge(f, it, '$link');
    };
    g.removeVertex(it);    
//    it.setProperty('code', it.code + ' mergePrev');
}
            ";
            }
            unset($actions['mergePrev']);
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

        if (isset($cdt['begin'])) {
            $qcdts[] = "has('begin', true)";
            unset($cdt['begin']);
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

        if (isset($cdt['filterOut'])) {
            if (is_string($cdt['filterOut'])) {
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