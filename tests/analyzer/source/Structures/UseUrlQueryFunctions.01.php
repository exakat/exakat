<?php
$data = array(
    'foo' => 'bar',
    'baz' => 'boom',
    'cow' => 'milk',
    'php' => 'hypertext processor'
);

// safe and efficient way to build a query string
echo http_build_query($data, '', '&') . PHP_EOL;

// slow way to produce a query string
foreach($data as $name => &$value) {
    $value = $name.'='.$value;
}
echo implode('&', $data) . PHP_EOL;

?>