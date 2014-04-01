<?php
    $a = 'B';

    if(!C($b, $c)) {
      throw new D('E');
    }

    try {
      $d = new F($e);
      $f = array(
        'G' => $g,
        'H' => $h,
        'I' => $i
      );
      $j = new J(K::$k);
      $l->L(M::$m, 'N');
      $n = $o->O('P', $p);
      return $q['Q'];
    }
    catch(R $r) {
      throw new S('T');
    }
    catch(U $s) {
      throw new V('W' . $t->X['Y']);
    }
    catch(Z $u) {
      throw new AA($v->AB());
    }
    catch(AC $w) {
      throw new AD($x->AE());
    }
    catch(AF $y) {
      throw new AG('AH');
    }
?>