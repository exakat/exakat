<?php
      try {
        $d        = new DateTime( $a, new DateTimeZone( $b ));
      }
      catch( Exception $e ) {
        $c = date( 'Y-m-d-H-i-s', strtotime( $d ));
      }
?>