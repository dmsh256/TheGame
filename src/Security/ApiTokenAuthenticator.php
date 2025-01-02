<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiTokenAuthenticator implements AccessTokenHandlerInterface
{
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (!$this->isValidToken($accessToken)) {
            throw new BadCredentialsException('Invalid token.');
        }

        return new UserBadge($accessToken, fn () => new class implements UserInterface {
            public function getRoles(): array
            {
                return ['ROLE_USER'];
            }

            public function eraseCredentials()
            {
            }

            public function getUserIdentifier(): string
            {
                return 'user123';
            }
        });
    }

    private function isValidToken(string $token): bool
    {
        return $token === 'my_secret_token';
    }
}
