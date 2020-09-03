<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Forms
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Forms;

use App\Core\Form\BaseForm;
use App\Core\Form\FormInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class RegistrationForm
 */
class RegistrationForm extends BaseForm implements FormInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * RegistrationForm constructor.
     *
     * @param UserRepositoryInterface   $repository
     * @param array                     $query
     * @param array                     $request
     * @param array                     $attributes
     * @param array                     $cookies
     * @param array                     $files
     * @param array                     $server
     * @param null                      $content
     */
    public function __construct(
        UserRepositoryInterface $repository,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        $this->repository = $repository;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'between:4,12',
                'regex:/^[\p{L}]+[\p{L}0-9]*?([_|-]{0,1}[\p{L}0-9])+$/',
                function ($key, $value) {
                    if ($this->repository->valueExist($key, $value)) {
                        $this->validator->errors()->add($key, "{$value} already exists.");
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                'email',
                function ($key, $value){
                    if ($this->repository->valueExist($key, $value)) {
                        $this->validator->errors()->add($key, "{$value} already exists");
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'between:6,16',
                'regex:/^[A-Za-z0-9]+[!@#$%^&*A-Za-z0-9]*?[A-Za-z0-9]+$/',
                'same:confirm'
            ],
        ];
    }

    /**
     * Get messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The login format is invalid. Login can contain latin and cyrillic characters, numbers and special characters like: -_',
            'password.regex' => 'The password format is invalid. Password can contain latin characters, numbers and special characters like: !@#$%^&*',
        ];
    }
}
