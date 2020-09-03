<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Commands\User
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);


namespace App\Commands\User;

use App\Commands\CommandInterface;
use App\Core\Jwt\JwtTheme;
use App\Entities\UserEntity;
use App\Core\Form\FormInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\InvalidAlgorithmException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * Class CreateCommand
 */
class CreateCommand implements CommandInterface
{
    /**
     * @var array
     */
    protected $form;

    /**
     * @var UserEntity
     */
    protected $entity;

    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * @var JwtTheme
     */
    protected $jwt;

    /**
     * CreateUserCommand constructor.
     *
     * @param array                     $form
     * @param JwtTheme                  $jwt
     * @param UserEntity                $entity
     * @param UserRepositoryInterface   $repository
     */
    public function __construct(
        array $form,
        JwtTheme $jwt,
        UserEntity $entity,
        UserRepositoryInterface $repository
    ) {
        $this->form = $form;
        $this->jwt = $jwt;
        $this->entity = $entity;
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException
     * @throws InvalidAlgorithmException
     */
    public function execute(): void
    {
        Arr::set($this->form, 'password', Hash::make($this->form['password']));

        $this->entity->hydrateData($this->form);
        $this->entity->touch();

        $this->generateJwtToken();

        if (!$this->repository->save($this->entity)) {
            throw new \RuntimeException('Cannot save user data.');
        }
    }

    /**
     * Generate new Jwt Auth token and refresh
     *
     * @throws InvalidAlgorithmException
     */
    protected function generateJwtToken(): void
    {
        $this->entity->setJwtUid((string)Uuid::uuid4());

        $this->jwt
            ->createToken(['uid' => $this->entity->getUid()], 0, $this->entity->getJwtUid())
            ->createRefreshToken(['jwt' => (string)$this->jwt->getToken()], 604800); // 604800 === one weak
    }
}
