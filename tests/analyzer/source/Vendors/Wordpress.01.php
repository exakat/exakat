<?php

//Usage of the WP_http class from Wordpress
$rags = array(
   'x' => '1',
   'y' => '2'
);
$url = 'http://www.example.com/';
$request = new WP_Http();
$result = $request->request( $url, array( 'method' => 'POST', 'body' => $body) );

new WP_HtttP();

?>