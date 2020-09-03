<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Http\Controllers
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Commands\User\CreateCommand;
use App\Commands\User\LoginCommand;
use App\Core\Jwt\JwtTheme;
use App\Entities\UserEntity;
use App\Forms\LoginForm;
use App\Forms\RegistrationForm;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * AuthController constructor.
     *
     * @param UserRepositoryInterface   $repository
     * @param Request                   $request
     */
    public function __construct(UserRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;

        $this->middleware('auth:api')->except(['signUp', 'signIn']);

        parent::__construct($request);
    }

    /**
     * Registration action
     *
     * @return JwtTheme
     */
    public function signUp(): JwtTheme
    {
        /** @var RegistrationForm $form */
        $form = \App::make(RegistrationForm::class);

        /** @var JwtTheme $jwt  */
        $jwt = \App::make(JwtTheme::class);

        /** @var CreateCommand $command */
        $command = \App::makeWith(CreateCommand::class, ['form' => $form->getData(), 'jwt' => $jwt]);
        $command->execute();

        return $jwt;
    }

    /**
     * Login action
     *
     * @return JwtTheme
     */
    public function signIn(): JwtTheme
    {
        /**
         * @var LoginForm $form
         *
         * Validate incoming form request
         * Form request was validated when we create class
         */
        $form = \App::make(LoginForm::class);

        /** @var UserEntity */
        $entity = $form->authenticateUser();

        /** @var JwtTheme $jwt  */
        $jwt = \App::make(JwtTheme::class);

        $command = \App::makeWith(LoginCommand::class, ['form' => $form->getData(), 'jwt' => $jwt, 'entity' => $entity]);
        $command->execute();

        return $jwt;
    }

    /**
     * logout action
     */
    public function logout()
    {
        /** @var UserEntity */
        \Auth::user()->setJwtUid(null);

        $this->repository->updateTokenUid(\Auth::user());
    }
}
