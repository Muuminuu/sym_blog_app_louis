<?php

namespace App\Model\User;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserResetPassword implements PasswordAuthenticatedUserInterface
{
    /**
     * @var string The hashed password
     */
    private ?string $password = null;

        /**
     * @var string The hashed password
     */
    private ?string $newPassword = null;


    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): static
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
