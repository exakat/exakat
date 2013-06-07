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
        
        for($i = 1; $i < 4; $i++) {
            if (!empty($this->conditions[$i])) {
                $cdt = $this->conditions[$i];
                $cdt['next'] = $i;
                $qcdts = array_merge($qcdts, $this->readConditions($cdt));

                $qcdts[] = "back('origin')";
            }
        }
        
        if (count(Token::$reserved) != 0) {
            $cdt['next'] = max(array_keys($this->conditions));
            $qcdts[] = "filter{!(it.token in ['".join("', '", Token::$reserved)."'])}";
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
        }
        
        if (isset($actions['atom'])) {
           $qactions[] = " /* atom */   it.setProperty('atom', '".$actions['atom']."')";
        }

        if (isset($actions['makeEdge'])) {
            foreach($actions['makeEdge'] as $destination => $label) {
                print "makeEdge : $label\n";
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
                    $d = str_repeat(".in('NEXT')", abs($destination));
                    $qactions[] = "g.addEdge(it, it".$d.".next(), '$label')";
                } else {
                    print "Ignoring addEdge for 0\n";
                }
            }
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

        if ($remainder = array_keys($cdt)) {
            print "Warning : the following ".count($remainder)." conditions were ignored : ".join(', ', $remainder)."\n";
        }
        
        return $qcdts;
    }
}

?>