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

        if (!empty($this->conditions[-1])) {
            $cdt = $this->conditions[-1];
            $cdt['previous'] = 1;
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));

            $qcdts[] = "back('origin')";
        }
        
        if (!empty($this->conditions[1])) {
            $cdt = $this->conditions[1];
            $cdt['next'] = 1;
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));

            $qcdts[] = "back('origin')";
        }

        if (!empty($this->conditions[2])) {
            $cdt = $this->conditions[2];
            $cdt['next'] = 2;
            $qcdts = array_merge($qcdts, $this->readConditions($cdt));

            $qcdts[] = "back('origin')";
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
        
        if (isset($actions['addEdge'])) {
            foreach($actions['addEdge'] as $destination => $label) {
                if ($destination > 0) {
                    $d = str_repeat(".out('NEXT')", $destination);
                } elseif ($destination < 0) {
                    $d = str_repeat(".in('NEXT')", abs($destination));
                } else {
                    print "Ignoring addEdge for 0\n";
                }
                $qactions[] = "    g.addEdge(it, it".$d.".next(), '$label')";
            }
        }

        if (isset($actions['changeNext'])) {
            foreach($actions['changeNext'] as $destination) {
                if ($destination > 0) {
                    $d = str_repeat(".out('NEXT')", $destination - 1);
                    $qactions[] = "
    g.addEdge(it, it$d.out('NEXT').out('NEXT').next(), 'NEXT');
    g.removeEdge(it$d.out('NEXT').outE('NEXT').next());
    g.removeEdge(it$d.outE('NEXT').next())\n";
                } elseif ($destination < 0) {
                    $d = str_repeat(".in('NEXT')", abs($destination) - 1);
                    $qactions[] = "
    g.addEdge(it$d.in('NEXT').in('NEXT').next(), it, 'NEXT'); 
    g.removeEdge(it$d.in('NEXT').inE('NEXT').next());
    g.removeEdge(it$d.inE('NEXT').next());\n";
                } else {
                    print "Ignoring changeNext for 0\n";
                }
            }
        }    
        
        if (isset($actions['cleansemicolon']) && $actions['cleansemicolon']) {
            $qactions[] = "
    it.out('NEXT').has('code',';').has('atom', null).each{ 
        g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
        g.removeEdge(it.inE('NEXT').next());
        g.removeEdge(it.outE('NEXT').next());

        g.removeVertex(it);
    }";
        }
        
        if (isset($actions['atom'])) {
           $qactions[] = "    it.setProperty('atom', '".$actions['atom']."')";
        }

        if (isset($actions['dropNext'])) {
            foreach($actions['dropNext'] as $id) {
                $d = str_repeat(".out('NEXT')", $id);
                $qactions[] = "
f = it$d.next();
f.outE('NEXT').each{
    g.addEdge(it.in('NEXT'), it.out('NEXT'), 'NEXT');
    g.removeEdge(it.outE('NEXT').next()); 
    g.removeEdge(it.inE('NEXT').next()); 
}
g.removeVertex(f);
           ";
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
        
        if (isset($cdt['code']) && is_array($cdt['code']) && !empty($cdt['code'])) {
            $qcdts[] = "filter{it.code in ['".join("', '", $cdt['code'])."']}";
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