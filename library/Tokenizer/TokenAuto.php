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

        if (!empty($this->conditions[-2])) {
            $cdt = $this->conditions[-2];
            $cdt['previous'] = 2;
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));

            $qcdts[] = "back('origin')";
        }

        if (!empty($this->conditions[-1])) {
            $cdt = $this->conditions[-1];
            $cdt['previous'] = 1;
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));

            $qcdts[] = "back('origin')";
        }
        
        for($i = 1; $i < 6; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['next'] = $i;
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }
        
        if (count(Token::$reserved) != 0) {
            $cdt = array();
            $cdt['next'] = max(array_keys($this->conditions)) + 1;
            $cdt['filterOut'] = Token::$reserved;
                        
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));
            $qcdts[] = "back('origin')";
        }
        
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
                    print "Ignoring order 0\n";
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

        if (isset($actions['transform'])) {
            $c = 0;
            foreach($actions['transform'] as $destination => $label) {
                $c++;
                
                if ($label == 'DROP') {
                    $qactions[] = "
/* makeEdge2 drop ($c) */
f = [];
it.out('NEXT').fill(f);
h = it;
f.each{
    i = it; 
    it.out('NEXT').each{ g.addEdge(h, it, 'NEXT');}

    g.removeVertex(i);
}

";
                } else {
                    $qactions[] = "
/* makeEdge2 out ($c) */
f =  it.out('NEXT').next();
g.addEdge(it, f, '$label');
g.removeEdge(f.inE('NEXT').next());

g.addEdge(it, f.out('NEXT').next(), 'NEXT');
g.removeEdge(f.outE('NEXT').next());

";
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
        
        if (isset($actions['insertEdge'])) {
            foreach($actions['insertEdge'] as $destination => $config) {
            if ($destination == 0) {
                list($atom, $link) = each($config);
                display("addEdge : $atom\n");
                $qactions[] = "
/* insertEdge out */
x = g.addVertex(null, [code:'void', atom:'$atom']);
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
x = g.addVertex(null, [code:'void', atom:'$atom']);
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
            list($atom, $link) = $actions['mergeNext'];
            
            $qactions[] = " /* mergeNext */ 
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
}
            ";
            unset($actions['mergeNext']);
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
            } else {
                $qcdts[] = "filter{it.atom != null || !(it.token in ['".join("', '", $cdt['filterOut'])."'])}";
            }

            unset($cdt['filterOut']);
        }

        if ($remainder = array_keys($cdt)) {
            print "Warning : the following ".count($remainder)." conditions were ignored : ".join(', ', $remainder)." (".get_class($this).")\n";
            print_r($cdt);
        }
        
        return $qcdts;
    }
}

?>