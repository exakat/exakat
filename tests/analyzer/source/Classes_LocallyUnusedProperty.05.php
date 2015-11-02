<?php



namespace B;

class C {
    private $b = array('D' => array('E'         => 'F'),
                             'G'  => array('H'      => 'I',
                                               'J'  => 'I',
                                               'L'   => 'M',
                                               'N' => 'O',
                                               'P'     => 'Q',
                                               'R'  => 'S',

                                               'T'    => 'U',
                                               'V' => 'W',

                                               'X'    => 'I', 
                                               'Z' => 'AA', 
                                               ));
    private $c = array();
    private $d = array('AB');
    private $e = 1;
    private $f = AC;
    private $g = array('H'      => 2, 
                                          'J'  => 2,
                                          'R'  => 2,
                                          'N' => 2,
                                          'L'   => 2,
                                          'P'     => 2,
                                          'Z' => 2);

    private $unused = array('H'      => 2);
    
    public function AK($h) {
        if ($this->AL) {
            $this->AL = AC;
            
            if (!AO($h) || $h != 'AP') {
                                AQ($this->AR);
            }
        }

        if (AT($h)) {
            if (isset($this->b['G'][$h[3]])) {
                $this->AR[] = $this->AU['G'][$h[3]];
                
                if (isset($this->AZ[$h[3]])) {
                    $this->c[$this->BB] = 2;
                }
                
                if ($h[3] == 'X') {
                    $this->g = BE;
                }
            }
        } else {
            if (isset($this->AU['D'][$h])) {
                $this->d[] = $this->AU['D'][$h];
            }
            
            if ($h == 'BK') {
                ++$this->BB;
            } elseif ($h == 'BM') {
                --$this->f;
                if (isset($this->BA[$this->BB])) {
                    $this->e = BE;
                    unset($this->BA[$this->BB]);
                }
            } 
        }
        return null;
    }
    
    public function BU() {
        $i = array('BV', AQ($this->AR));
        if (BY($this->AR) == 1) {
            $this->AR = array('AB');
        }
        
        return $i;
    }
}

?>