<?php

  $handle = kadm5_init_with_password("afs-1", "GONICUS.LOCAL", "admin/admin", "password");

  print "<h1>get_principals</h1>\n";
  $principals = kadm5_get_principals($handle);
  for( $i=0; $i<count($principals); $i++)
      print "$principals[$i]<br>\n";

  print "<h1>get_policies</h1>\n";
  $policies = kadm5_get_policies($handle);
  for( $i=0; $i<count($policies); $i++)
      print "$policies[$i]<br>\n";

  print "<h1>get_principal burbach@GONICUS.LOCAL</h1>\n";

  $options = kadm5_get_principal($handle, "burbach@GONICUS.LOCAL" );
  $keys = array_keys($options);
  for( $i=0; $i<count($keys); $i++) {
    $value = $options[$keys[$i]];
    print "$keys[$i]: $value<br>\n";
  }

  $options = array(KADM5_PRINC_EXPIRE_TIME => 0);
  kadm5_modify_principal($handle, "burbach@GONICUS.LOCAL", $options);

  kadm5_destroy($handle);
?>