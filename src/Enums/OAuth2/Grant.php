<?php

declare(strict_types=1);

namespace Saloon\Enums\OAuth2;

enum Grant: string
{
    case AuthorizationCode = 'authorization_code';
    case ClientCredentials = 'client_credentials';
}
