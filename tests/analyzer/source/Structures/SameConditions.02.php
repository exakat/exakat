<?php

			if($b > 1){
				$c = 'B';
			} else {
				if($b > 1){
					$c = 'E';
				}
				$b = C($d,'D');
			}

				if( $e != 'F' ) {
					$f = G( 'E', $e );
					foreach( $f as $g ) {
						I( 'J', $g, $h );
						if( !empty( $h[2] ) ) {
						    $i = 'K' . $h[2];
						    if( L( $i ) ) {
							$j = $j . $i . 'M';
						    }							
						}
					}
				} else {
				    $e = N( 'O', 3, 4, P );
				    if( $e != 'F' ) {
					$f = G( 'E', $e );
					foreach( $f as $g ) {
						if( !I( 'U', $g, $h ) ) {
						    I( 'J', $g, $h );
						}
						if( !empty( $h[2] ) ) {
						    $i = 'K' . $h[2];
						    if( L( $i ) ) {
							$j = $j . $i . 'M';
						    }
						}
					}
				    }
				}

// OK
			if($b > 2){
				$c = 'B';
			} else {
				if($b > 2){
					$c = 'E';
				}
			}

// OK
			if($b > 3){
				if($b > 3){
					$c = 'E';
				}
			} else {
				$c = 'B';
			}

?>