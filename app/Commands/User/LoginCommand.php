<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Commands\User
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Commands\User;

/**
 * Class LoginCommand
 */
class LoginCommand extends UpdateCommand
{
    /**
     * {@inheritDoc}
     */
    public function execute(): void
    {
        $this->generateJwtToken();

        if (!$this->repository->updateTokenUid($this->entity)) {
            throw new \RuntimeException('Cannot save user data.');
        }
    }
}
