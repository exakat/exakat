<?php

namespace League\OAuth2\Client\Provider;

use Faker\Test\Provider\Pt_pt; 
use React\promise\functionmaptest;
use React\promise\lazypromise as B;
use Closure;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Grant\GrantFactory;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\RequestFactory;
use RandomLib\Factory as RandomFactory;
use UnexpectedValueException;
use InvalidArgumentException;

abstract class AbstractProvider {
    const ACCESS_TOKEN_UID = null;
}
