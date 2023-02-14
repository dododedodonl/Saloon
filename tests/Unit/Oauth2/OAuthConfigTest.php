<?php

declare(strict_types=1);

use Saloon\Enums\OAuth2\Grant;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Exceptions\OAuthConfigValidationException;

test('all default properties are correct and all getters and setters work properly', function () {
    $config = new OAuthConfig;

    expect($config->getGrant())->toEqual(Grant::AuthorizationCode);
    expect($config->getClientId())->toEqual('');
    expect($config->getClientSecret())->toEqual('');
    expect($config->getRedirectUri())->toEqual('');
    expect($config->getAuthorizeEndpoint())->toEqual('authorize');
    expect($config->getTokenEndpoint())->toEqual('token');
    expect($config->getUserEndpoint())->toEqual('user');
    expect($config->getDefaultScopes())->toEqual([]);

    $grant = Grant::ClientCredentials;
    $clientId = 'client-id';
    $clientSecret = 'client-secret';
    $redirectUri = 'https://my-app.saloon.dev/auth/callback';
    $authorizeEndpoint = 'auth/authorize';
    $tokenEndpoint = 'auth/token';
    $userEndpoint = 'auth/user';
    $defaultScopes = ['profile'];

    expect($config->setGrant($grant))->toEqual($config);
    expect($config->setClientId($clientId))->toEqual($config);
    expect($config->setClientSecret($clientSecret))->toEqual($config);
    expect($config->setRedirectUri($redirectUri))->toEqual($config);
    expect($config->setAuthorizeEndpoint($authorizeEndpoint))->toEqual($config);
    expect($config->setTokenEndpoint($tokenEndpoint))->toEqual($config);
    expect($config->setUserEndpoint($userEndpoint))->toEqual($config);
    expect($config->setDefaultScopes($defaultScopes))->toEqual($config);

    expect($config->getGrant())->toEqual($grant);
    expect($config->getClientSecret())->toEqual($clientSecret);
    expect($config->getRedirectUri())->toEqual($redirectUri);
    expect($config->getAuthorizeEndpoint())->toEqual($authorizeEndpoint);
    expect($config->getTokenEndpoint())->toEqual($tokenEndpoint);
    expect($config->getUserEndpoint())->toEqual($userEndpoint);
    expect($config->getDefaultScopes())->toEqual($defaultScopes);
});

test('make method creates an instance of OAuthConfig', function () {
    expect(OAuthConfig::make())->toBeInstanceOf(OAuthConfig::class);
});

test('it will throw an exception if you do not specify the client id', function () {
    $config = new OAuthConfig;
    $config->validate();
})->throws(OAuthConfigValidationException::class, 'The Client ID is empty or has not been provided.');

test('it will throw an exception if you do not specify the client secret', function () {
    $config = new OAuthConfig;
    $config->setClientId('client-id');

    $config->validate();
})->throws(OAuthConfigValidationException::class, 'The Client Secret is empty or has not been provided.');

test('it will throw an exception if you do not specify the redirect uri with the authorization code grant', function () {
    $config = new OAuthConfig;

    $config->setGrant(Grant::AuthorizationCode)
        ->setClientId('client-id')
        ->setClientSecret('client-secret');

    $config->validate();
})->throws(OAuthConfigValidationException::class, 'The Redirect URI is empty or has not been provided.');

test('it will now throw an exception if you do not specify the redirect uri with the client credentials grant', function () {
    $config = new OAuthConfig;

    $config->setGrant(Grant::ClientCredentials)
        ->setClientId('client-id')
        ->setClientSecret('client-secret');

    expect($config->validate())->toBeTrue();
});
