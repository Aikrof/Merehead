<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Rest\Traits
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Rest\Traits;

/**
 * Trait AuthenticatableTrait
 *
 * used to bypass the \Illuminate\Contracts\Auth\Authenticatable interface
 *
 * This need, to work with `laravel guard` to Authenticate user
 *
 * @see \Illuminate\Contracts\Auth\Authenticatable
 */
trait AuthenticatableTrait
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return $this->getLogin();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getUid();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->getPassword();
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken(): ?string
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setRememberToken($value): void
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return '';
    }
}