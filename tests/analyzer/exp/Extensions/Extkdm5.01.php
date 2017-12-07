<?php

$expected     = array('kadm5_init_with_password("afs-1", "GONICUS.LOCAL", "admin/admin", "password")',
                      'kadm5_get_principals($handle)',
                      'kadm5_get_policies($handle)',
                      'kadm5_get_principal($handle, "burbach@GONICUS.LOCAL")',
                      'kadm5_modify_principal($handle, "burbach@GONICUS.LOCAL", $options)',
                      'kadm5_destroy($handle)',
                      'KADM5_PRINC_EXPIRE_TIME',
                     );

$expected_not = array('array_keys',
                     );

?>