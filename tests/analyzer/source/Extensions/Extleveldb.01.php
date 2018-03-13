<?php

$leveldb_path = get_leveldb_path();
$db = new LevelDB($leveldb_path);
var_dump($db->set('key', 'value'));
var_dump($db->get('key'));
var_dump($db->get('non-exists-key'));
var_dump($db->put('name', 'reeze'));
var_dump($db->get('name'));
var_dump($db->delete('name'));
var_dump($db->get('name'));
?>