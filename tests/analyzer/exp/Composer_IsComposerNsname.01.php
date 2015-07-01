<?php

$expected     = array('Faker\Test\Provider\Pt_pt', 
                      'React\promise\lazypromise as B', 
                      'React\promise\functionmaptest', 
                      'GuzzleHttp\Exception\BadResponseException', 
                      'RandomLib\Factory as RandomFactory', 
                      'GuzzleHttp\Client as HttpClient', 
                      'GuzzleHttp\ClientInterface as HttpClientInterface', 
                      'Psr\Http\Message\ResponseInterface', 
                      'Psr\Http\Message\RequestInterface');

$expected_not = array('Closure',
                      'UnexpectedValueException',
                      'InvalidArgumentException'
);

?>