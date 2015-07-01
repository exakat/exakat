<?php

$expected     = array('League\OAuth2\Client\Token\AccessToken', 
                      'League\OAuth2\Client\Grant\GrantFactory', 
                      'League\OAuth2\Client\Tool\RequestFactory', 
                      'League\OAuth2\Client\Provider\Exception\IdentityProviderException');

$expected_not = array('Faker\Test\Provider\Pt_pt',
                      'React\promise\functionmaptest',
                      'React\promise\lazypromise as B',
                      'Closure',
                      'GuzzleHttp\Client',
                      'HttpClient',
                      'GuzzleHttp\ClientInterface',
                      'HttpClientInterface',
                      'GuzzleHttp\Exception\BadResponseException'
);

?>