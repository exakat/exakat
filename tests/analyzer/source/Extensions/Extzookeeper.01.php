<?php
$zookeeper = new Zookeeper('locahost:2181');
$aclArray = array(
  array(
    'perms'  => Zookeeper::PERM_ALL,
    'scheme' => 'world',
    'id'     => 'anyone',
  )
);
$path = '/path/to/newnode';
$realPath = $zookeeper->create($path, null, $aclArray);
if ($realPath)
  echo $realPath;
else
  echo 'ERR';
  
  zookeeper_distribute();
?>