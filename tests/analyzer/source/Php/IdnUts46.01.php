<?php

echo idn_to_ascii('täst.de'); 
echo idn_to_ascii('täst.de', IDNA_DEFAULT); 
echo idn_to_ascii('täst.de', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46); 
echo idn_to_utf8('täst.de', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46, $a); 
echo idn_to_ascii('täst.de', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46, $a, $b); 
echo A::idn_to_ascii('täst.de', IDNA_DEFAULT ); 
