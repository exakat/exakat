<?php

$expected     = array('idn_to_ascii(\'täst.de\', IDNA_DEFAULT)',
                      'idn_to_ascii(\'täst.de\')',
                     );

$expected_not = array('idn_to_ascii(\'täst.de\', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46)',
                      'idn_to_utf8(\'täst.de\', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46, $a)',
                      'idn_to_ascii(\'täst.de\', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46, $a, $b)',
                     );

?>