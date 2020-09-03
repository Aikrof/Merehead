<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Forms
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Forms;

use App\Entities\UserEntity;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginForm
 */
class LoginForm extends RegistrationForm
{
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'between:6,16',
                'regex:/^[A-Za-z0-9]+[!@#$%^&*A-Za-z0-9]*?[A-Za-z0-9]+$/',
            ],
        ];
    }

    /**
     * @return UserEntity
     */
    public function authenticateUser(): UserEntity
    {
        $messageBag = $this->validator->getMessageBag();

        $email = $this->get('email');

        /** @var UserEntity|null $user */
        $user = $this->repository->find($email, 'email');
        if ($user === null) {
            $messageBag->add('email', "User with email: {$email} does not exists.");
        }
        else {
            if (!$this->comparePasswords($this->get('password'), $user->getPassword())) {
                $messageBag->add('password', 'Password does not match this account.');
            }
        }

        if (!empty($messageBag->getMessages())) {
            $this->sendException($messageBag);
        }

        return $user;
    }

    /**
     * Compare passwords
     *
     * @param string $requestPassword
     * @param string $userPassword
     *
     * @return bool
     */
    private function comparePasswords(string $requestPassword, string $userPassword): bool
    {
        return Hash::check($requestPassword, $userPassword);
    }
}
