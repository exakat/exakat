<?php
  $dbconn = pg_connect("dbname=publisher") or die("Could not connect");

  if (!pg_connection_busy($dbconn)) {
      pg_send_query($dbconn, "select * from authors; select count(*) from authors;");
  }
  
  $res1 = pg_get_result($dbconn);
  echo "First call to pg_get_result(): $res1\n";
  $rows1 = pg_num_rows($res1);
  echo "$res1 has $rows1 records\n\n";
  
  $res2 = pg_get_result($dbconn);
  echo "Second call to pg_get_result(): $res2\n";
  $rows2 = pg_num_rows($res2);
  echo "$res2 has $rows2 records\n";
?>